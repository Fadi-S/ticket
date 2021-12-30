<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\EventType;
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

        Period::create([
            'name' => 'Test Period',
            'type_id' => 1,
            'start' => now()->copy(),
            'end' => now()->copy()->endOfMonth(),
        ]);

        EventType::query()->where('id', 1)->update(['allows_exception' => false]);
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

        $this->assertEquals(0, $this->user->tickets()->event(1));

        $this->assertFalse($this->reserveInNewEvent(now()->endOfMonth()->subHours(4)));

        $this->assertEquals(0, $this->user->tickets()->event(1));
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
            'event_id' => Event::factory()->create(['number_of_places' => 1])->id
        ]);

        $this->assertNotFalse($this->user->reserveIn($ticket));

        $admin = User::factory()->create();
        $admin->assignRole('super-admin');
        $this->actingAs($admin);

        $john = User::factory()->create();

        $this->assertNotFalse($john->canReserveIn($ticket->event));
    }

    /** @test */
    function there_must_be_enough_space_to_reserve_a_ticket_to_user()
    {
        $ticket = Ticket::factory()->create([
            'event_id' => Event::factory()->create(['number_of_places' => 1])->id
        ]);

        $this->assertNotFalse($this->user->reserveIn($ticket));

        $john = User::factory()->create();

        $this->assertFalse($john->reserveIn($ticket));
    }

    private function reserveInNewEvent(Carbon $time=null)
    {
        $event = Event::factory()->create([
            'start' => $time ?? now()->hours(8),
            'end' => $time ?? now()->hours(10),
        ]);

        $ticket = Ticket::factory()->create([
            'event_id' => $event->id
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
            'type_id' => 1,
            'start' => $startOfFirst->copy(),
            'end' => $endOfFirst,
        ]);

        Period::create([
            'name' => "Test Period 2",
            'type_id' => 1,
            'start' => $startOfSecond,
            'end' => $endOfSecond,
        ]);

        $EventAtTheStartOfFirst = Event::factory()->create([
            'start' => $startOfFirst->copy()->hours(8),
            'end' => $startOfFirst->copy()->hours(10),
        ]);

        $EventAtTheStartOfSecond = Event::factory()->create([
            'start' => $startOfSecond->copy()->hours(8),
            'end' => $startOfSecond->copy()->hours(10),
        ]);

        $this->travelTo($startOfFirst->copy()->subDays(2));
        $ticket1 = Ticket::factory()->create(['event_id' => $EventAtTheStartOfFirst]);

        $this->assertEquals(1, $this->user->tickets()->event(1, $startOfFirst));
        $this->assertNotFalse($this->user->reserveIn($ticket1));
        $this->assertEquals(0, $this->user->tickets()->event(1, $startOfFirst));


        $this->travelTo($startOfSecond->copy()->subDays(2));
        $ticket2 = Ticket::factory()->create(['event_id' => $EventAtTheStartOfSecond]);

        $this->assertEquals(1, $this->user->tickets()->event(1, $startOfSecond));
        $this->assertNotFalse($this->user->reserveIn($ticket2));
        $this->assertEquals(0, $this->user->tickets()->event(1, $startOfSecond));
    }

    /** @test */
    function events_in_the_start_and_end_of_period_counts_towards_user_quota()
    {
        $date = now()->hours(8);

        Period::query()->delete();

        Period::create([
            'name' => 'Test Period',
            'type_id' => 1,
            'start' => $date->copy()->addDays(2)->startOfDay(),
            'end' => $date->copy()->addDays(2)->addWeek()->endOfDay(),
        ]);

        $eventInBeginning = Event::factory()->create([
            'start' => $date->copy()->addDays(2)->hours(8),
            'end' => $date->copy()->addDays(2)->addHours(2),
        ]);

        $eventInEnd = Event::factory()->create([
            'start' => $date->copy()->addDays(2)->addWeek(),
            'end' => $date->copy()->addDays(2)->addWeek()->addHour(),
        ]);

        foreach ([$eventInBeginning, $eventInEnd] as $event) {
            $event->type->allows_exception = false;
            $event->type->save();
            $ticket = Ticket::factory()->create([
                'event_id' => $event->id
            ]);

            $this->assertEquals(1, $this->user->tickets()->event(1, $event->start));

            $this->assertNotFalse($this->user->reserveIn($ticket));

            $this->assertEquals(0, $this->user->tickets()->event(1, $event->start));

            $ticket->cancel();
        }

    }

    private function fillTickets()
    {
        $period = Period::current(1);

        for($i=1; $i<=5; $i++)
            $this->reserveInNewEvent($period->end->endOfDay()->subDays(1 * $i));
    }
}
