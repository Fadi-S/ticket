<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\Mass;
use App\Models\Period;
use App\Models\Reservation;
use App\Models\Ticket;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MassReservationTest extends TestCase
{
    use DatabaseMigrations;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->travelTo(now()->startOfMonth());

        $this->user = User::factory()->create();
        $this->user->assignRole('user');
        $this->actingAs($this->user);

    }

    /** @test */
    function a_user_can_reserve_in_an_event()
    {
        $ticket = Ticket::factory()->create();

        $this->assertCount(0, $this->user->reservations);

        $this->user->reserveIn($ticket);

        $this->assertCount(1, $this->user->refresh()->reservations);
    }

    /** @test */
    function a_user_must_have_enough_tickets()
    {
        $this->assertCount(0, $this->user->reservations);

        $this->fillTickets();

        $this->assertEquals(0, $this->user->tickets()->mass());

        $this->assertFalse($this->reserveInNewEvent(now()->endOfMonth()->subHours(4)));

        $this->assertEquals(0, $this->user->tickets()->mass());
    }

    /** @test */
    function a_user_cant_reserve_in_the_same_event_twice()
    {
        $ticket = Ticket::factory()->create();

        $this->assertInstanceOf(Reservation::class, $this->user->reserveIn($ticket));

        $this->assertEquals(1, $this->user->reservations()->count());

        $this->assertFalse($this->user->reserveIn($ticket));
    }

    /** @test */
    function an_admin_reserving_to_user_can_bypass_ticket_limits()
    {
        $admin = User::factory()->create();
        $admin->assignRole('super-admin');
        $this->actingAs($admin);

        $this->fillTickets();

        $this->assertNotFalse($this->reserveInNewEvent(now()->endOfMonth()->subHours(10)));
    }

    /** @test */
    function an_admin_reserving_to_user_can_bypass_place_limits()
    {
        $ticket = Ticket::factory()->create([
            'event_id' => Mass::factory()->create(['number_of_places' => 1])->id
        ]);

        $this->assertNotFalse($this->user->reserveIn($ticket));

        $admin = User::factory()->create();
        $admin->assignRole('super-admin');
        $this->actingAs($admin);

        $john = User::factory()->create();

        $this->assertNotFalse($john->canReserveIn($ticket->event->specific()));
    }

    /** @test */
    function there_must_be_enough_space_to_reserve_a_ticket_to_user()
    {
        $ticket = Ticket::factory()->create([
            'event_id' => Mass::factory()->create(['number_of_places' => 1])->id
        ]);

        $this->assertNotFalse($this->user->reserveIn($ticket));

        $john = User::factory()->create();

        $this->assertFalse($john->reserveIn($ticket));
    }

    private function reserveInNewEvent(Carbon $time=null)
    {
        $mass = Mass::factory()->create([
            'start' => $time ?? now()->addHours(10),
            'end' => $time ?? now()->addHours(12),
        ]);

        $ticket = Ticket::factory()->create([
            'event_id' => $mass->id
        ]);

        return $this->user->reserveIn($ticket);
    }

    /** @test */
    function events_in_the_start_and_end_of_period_counts_towards_user_quota()
    {
        $date = now()->hours(8);

        Period::create([
            'name' => 'Test Period',
            'start' => $date->startOfDay(),
            'end' => $date->copy()->addWeek()->endOfDay(),
        ]);

        $eventInBeginning = Mass::factory()->create([
            'start' => $date->copy()->hours(8),
            'end' => $date->copy()->addHours(2),
        ]);

        $eventInEnd = Mass::factory()->create([
            'start' => $date->copy()->addWeek(),
            'end' => $date->copy()->addWeek()->addHour(),
        ]);

        config()->set('settings.allow_for_exceptions', false);

        foreach ([$eventInBeginning, $eventInEnd] as $event) {
            $ticket = Ticket::factory()->create([
                'event_id' => $event->id
            ]);

            $this->assertEquals(1, $this->user->tickets()->mass($event->start));

            $this->assertNotFalse($this->user->reserveIn($ticket));

            $this->assertEquals(0, $this->user->tickets()->mass($event->start));

            $ticket->cancel();
        }

    }

    private function fillTickets()
    {
        for($i=1; $i<=5; $i++)
            $this->reserveInNewEvent(now()->endOfMonth()->subDays(3 * $i));
    }
}
