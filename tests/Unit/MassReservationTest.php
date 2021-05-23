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

        $this->user = User::factory()->create(['name' => 'Test Name Full', 'arabic_name' => 'تيست اسم ثلاثي']);
        $this->user->assignRole('user');
        $this->actingAs($this->user);

        config(['settings.mass.max_reservations_per_period' => 1]);
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
    function a_user_can_reserve_in_end_of_period_and_start_of_the_other_period()
    {
        $startOfFirst = Carbon::parse('25th April 2002');
        $endOfFirst = $startOfFirst->copy()->addWeek();

        $startOfSecond = $endOfFirst->copy()->addDay();
        $endOfSecond = $startOfSecond->copy()->addWeek();

        Period::create([
            'name' => "Test Period 1",
            'start' => $startOfFirst->copy(),
            'end' => $endOfFirst,
        ]);

        Period::create([
            'name' => "Test Period 2",
            'start' => $startOfSecond,
            'end' => $endOfSecond,
        ]);

        $massAtTheStartOfFirst = Mass::factory()->create([
            'start' => $startOfFirst->copy()->hours(8),
            'end' => $startOfFirst->copy()->hours(10),
        ]);

        $massAtTheStartOfSecond = Mass::factory()->create([
            'start' => $startOfSecond->copy()->hours(8),
            'end' => $startOfSecond->copy()->hours(10),
        ]);

        $this->travelTo($startOfFirst->copy()->subDays(2));
        $ticket1 = Ticket::factory()->create(['event_id' => $massAtTheStartOfFirst]);

        $this->assertEquals(1, $this->user->tickets()->mass($startOfFirst));
        $this->assertNotFalse($this->user->reserveIn($ticket1));
        $this->assertEquals(0, $this->user->tickets()->mass($startOfFirst));


        $this->travelTo($startOfSecond->copy()->subDays(2));
        $ticket2 = Ticket::factory()->create(['event_id' => $massAtTheStartOfSecond]);

        $this->assertEquals(1, $this->user->tickets()->mass($startOfSecond));
        $this->assertNotFalse($this->user->reserveIn($ticket2));
        $this->assertEquals(0, $this->user->tickets()->mass($startOfSecond));
    }

    /** @test */
    function events_in_the_start_and_end_of_period_counts_towards_user_quota()
    {
        $date = now()->addDay()->hours(8);

        Period::create([
            'name' => 'Test Period',
            'start' => $date->copy()->addDay()->startOfDay(),
            'end' => $date->copy()->addDay()->addWeek()->endOfDay(),
        ]);

        $eventInBeginning = Mass::factory()->create([
            'start' => $date->copy()->addDay()->hours(8),
            'end' => $date->copy()->addDay()->addHours(2),
        ]);

        $eventInEnd = Mass::factory()->create([
            'start' => $date->copy()->addDay()->addWeek(),
            'end' => $date->copy()->addDay()->addWeek()->addHour(),
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
