<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\Mass;
use App\Models\Reservation;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use DatabaseMigrations;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = User::factory()->create();
        $this->user->assignRole('user');
        $this->actingAs($this->user);
    }

    /** @test */
    function a_user_can_reserve_in_an_event()
    {
        $mass = Mass::factory()->create(['type_id' => 1]);

        $this->assertCount(0, $this->user->reservations);

        $this->user->reserveIn($mass);

        $this->assertCount(1, $this->user->refresh()->reservations);
    }

    /** @test */
    function a_user_must_have_enough_tickets()
    {
        $this->assertCount(0, $this->user->reservations);

        $this->travelTo(now()->endOfMonth());

        for($i=0; $i<5; $i++)
            $this->reserveInNewEvent(now()->startOfMonth()->addDays(6 * $i));

        $this->assertEquals(5, $this->user->reservationsFromMonth(now())->count());

        $this->assertFalse($this->reserveInNewEvent(now()->startOfMonth()));

        $this->assertEquals(5, $this->user->reservationsFromMonth(now())->count());
    }

    /** @test */
    function a_user_cant_reserve_in_the_same_event_twice()
    {
        $mass = Mass::factory()->create(['type_id' => 1]);

        $this->assertInstanceOf(Reservation::class, $this->user->reserveIn($mass));

        $this->assertEquals(1, $this->user->reservations()->count());

        $this->assertFalse($this->user->reserveIn($mass));
    }

    /** @test */
    function a_user_reserving_before_event_within_12hrs_can_have_an_exception()
    {
        $this->travelTo(now()->endOfMonth());

        for($i=0; $i<5; $i++)
            $this->reserveInNewEvent(now()->startOfMonth()->addDays(6 * $i));

        $this->assertNotFalse($this->reserveInNewEvent()); // Exception 1
        $this->assertNotFalse($this->reserveInNewEvent()); // Exception 2
    }

    /** @test */
    function an_admin_reserving_to_user_can_bypass_ticket_limits()
    {
        $admin = User::factory()->create();
        $admin->assignRole('super-admin');
        $this->actingAs($admin);

        $this->travelTo(now()->endOfMonth());

        for($i=0; $i<5; $i++)
            $this->reserveInNewEvent(now()->startOfMonth()->addDays(6 * $i));

        $this->assertNotFalse($this->reserveInNewEvent(now()->startOfMonth()));
    }

    /** @test */
    function an_admin_reserving_to_user_can_bypass_place_limits()
    {
        $mass = Mass::factory()->create([
            'type_id' => 1,
            'number_of_places' => 1,
        ]);

        $this->assertNotFalse($this->user->reserveIn($mass));

        $admin = User::factory()->create();
        $admin->assignRole('super-admin');
        $this->actingAs($admin);

        $john = User::factory()->create();

        $this->assertNotFalse($john->reserveIn($mass));
    }

    /** @test */
    function there_must_be_enough_space_to_reserve_a_ticket_to_user()
    {
        $mass = Mass::factory()->create([
            'type_id' => 1,
            'number_of_places' => 1,
        ]);

        $this->assertNotFalse($this->user->reserveIn($mass));

        $john = User::factory()->create();

        $this->assertFalse($john->reserveIn($mass));
    }

    private function reserveInNewEvent(Carbon $time=null)
    {
        $mass = Mass::factory()->create([
            'type_id' => 1,
            'start' => $time,
            'end' => $time ? $time->addHours(2) : null,
        ]);

        return $this->user->reserveIn($mass);
    }
}