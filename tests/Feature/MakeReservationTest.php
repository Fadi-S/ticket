<?php

namespace Tests\Feature;

use App\Http\Livewire\MakeReservation;
use App\Models\Event;
use App\Models\Mass;
use App\Models\Reservation;
use App\Models\Ticket;
use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MakeReservationTest extends TestCase
{
    use DatabaseMigrations;

    private Event $event;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->event = Mass::factory()->create();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    function must_choose_event()
    {
        $this->setRole('user');

        Livewire::test(MakeReservation::class)
            //->call('setEvent', $this->event->id)
            ->call('save')
            ->assertHasErrors('event');
    }

    /** @test */
    function must_choose_users()
    {
        $this->setRole('user');

        Livewire::test(MakeReservation::class)
            ->call('setEvent', $this->event->id)
            ->call('removeUser', $this->user->id)
            ->call('save')
            ->assertHasErrors('users');
    }

    /** @test */
    function user_can_reserve_in_event()
    {
        $this->setRole('user');

        $this->assertEquals(0, Reservation::count());

        Livewire::test(MakeReservation::class)
            ->call('setEvent', $this->event->id)
            ->call('save')
            ->assertRedirect();

        $this->assertEquals(1, Reservation::count());
    }

    /** @test */
    function user_can_reserve_for_friends()
    {
        $this->setRole('user');

        $this->assertEquals(0, Reservation::count());
        $this->assertEquals(0, Ticket::count());

        $john = User::factory()->create();

        $john->forceAddFriend($this->user);

        Livewire::test(MakeReservation::class)
            ->call('toggleUser', $john)
            ->fireEvent('set:event', $this->event->id)
            ->call('save')
            ->assertRedirect();

        $this->assertEquals(2, Reservation::count());
        $this->assertEquals(1, Ticket::count());
    }

    /** @test */
    function user_can_only_search_for_friends()
    {
        $this->setRole('user');

        $john = User::factory()->create(['name' => 'Test John']);
        $mary = User::factory()->create(['name' => 'Test Mary']);

        $john->forceAddFriend($this->user);

        Livewire::test(MakeReservation::class)
            ->set('search', 'Test')
            ->assertSee('Test John')
            ->assertDontSee('Test Mary');

        $mary->forceAddFriend($this->user);

        Livewire::test(MakeReservation::class)
            ->set('search', 'Test')
            ->assertSee('Test John')
            ->assertSee('Test Mary');
    }

    /** @test */
    function user_cant_reserve_for_none_friends()
    {
        $this->setRole('user');

        $this->assertEquals(0, Reservation::count());
        $this->assertEquals(0, Ticket::count());

        $john = User::factory()->create();

        $john->addFriend($this->user);

        Livewire::test(MakeReservation::class)
            ->call('toggleUser', $john)
            ->fireEvent('set:event', $this->event->id)
            ->call('save')
            ->assertDispatchedBrowserEvent('open', "You are not friends with $john->name");


        $this->assertEquals(0, Reservation::count());
        $this->assertEquals(0, Ticket::count());
    }

    /** @test */
    function user_agent_admin_cant_reserve_for_themselves_more_than_the_allowed_maximum()
    {
        $roles = ['agent', 'super-admin', 'user'];

        foreach ($roles as $role) {
            $this->setRole($role);

            $this->assertEquals(0, Reservation::count());

            $maximum = Mass::maxReservations();

            $this->reserveMaximum($maximum);

            $mass = Mass::factory()->create([
                'start' => now()->startOfMonth()->addDays(2 * ($maximum + 1)),
                'end' => now()->startOfMonth()->addDays(2 * ($maximum + 1))->addHours(2),
            ]);

            Livewire::test(MakeReservation::class)
                ->fireEvent('set:event', $mass->id)
                ->call('removeUser', $this->user->id)
                ->call('toggleUser', $this->user)
                ->call('save')
                ->assertDispatchedBrowserEvent('open');

            $this->assertEquals($maximum, Reservation::count());

            Ticket::all()->each->cancel();
        }
    }

    /** @test */
    function user_cant_reserve_in_a_passed_event()
    {
        $this->travelTo(now()->startOfMonth()->addDays(15));

        $mass = Mass::factory()->create([
            'start' => now()->startOfMonth(),
            'end' => now()->startOfMonth()->addHours(2),
        ]);

        Livewire::test(MakeReservation::class)
            ->fireEvent('set:event', $mass->id)
            ->call('save')
            ->assertDispatchedBrowserEvent('open');

        $this->assertEquals(0, Reservation::count());
    }

    /** @test */
    function event_must_be_published()
    {
        $mass = Mass::factory()->create([
            'published_at' => now()->addDays(5),
        ]);

        Livewire::test(MakeReservation::class)
            ->fireEvent('set:event', $mass->id)
            ->call('save')
            ->assertDispatchedBrowserEvent('open');

        $this->assertEquals(0, Reservation::count());
    }

    /** @test */
    function admin_or_agent_can_reserve_for_user()
    {
        $roles = ['agent', 'super-admin'];

        $john = User::factory()->create();
        $mary = User::factory()->create();
        $george = User::factory()->create();

        foreach ($roles as $role) {
            $this->setRole($role);

            Livewire::test(MakeReservation::class)
                ->fireEvent('set:event', $this->event->id)
                ->call('toggleUser', $john)
                ->call('toggleUser', $mary)
                ->call('toggleUser', $george)
                ->call('save')
                ->assertRedirect();

            $this->assertEquals(3, Reservation::count());

            $ticket = Ticket::first();
            $ticket->cancel();
        }
    }

    /** @test */
    function admin_or_agent_can_search_all_users()
    {
        $roles = ['agent', 'super-admin'];

        $john = User::factory()->create(['name' => 'Test John']);
        $mary = User::factory()->create(['name' => 'Test Mary']);
        $george = User::factory()->create(['name' => 'Test George']);

        foreach ($roles as $role) {
            $this->setRole($role);

            Livewire::test(MakeReservation::class)
                ->set('search', 'Test')
                ->assertSee('Test John')
                ->assertSee('Test Mary')
                ->assertSee('Test George');
        }
    }

    /** @test */
    function admin_or_agent_can_reserve_for_himOrHerself()
    {
        $roles = ['agent', 'super-admin'];

        foreach ($roles as $role) {
            $this->setRole($role);
            $this->assertEquals(0, Reservation::count());

            Livewire::test(MakeReservation::class)
                ->call('setEvent', $this->event->id)
                ->call('toggleUser', $this->user)
                ->call('save')
                ->assertRedirect();

            $this->assertEquals(1, Reservation::count());

            Ticket::first()->cancel();
        }
    }

    /** @test */
    function agent_cant_reserve_for_users_more_than_the_allowed_maximum()
    {
        $this->setRole('agent');

        $john = User::factory()->create();

        $maximum = Mass::maxReservations();
        $this->reserveMaximum($maximum, $john);

        $mass = Mass::factory()->create([
            'start' => now()->startOfMonth()->addDays(2 * ($maximum + 1)),
            'end' => now()->startOfMonth()->addDays(2 * ($maximum + 1))->addHours(2),
        ]);

        Livewire::test(MakeReservation::class)
            ->fireEvent('set:event', $mass->id)
            ->call('toggleUser', $john)
            ->call('save')
            ->assertDispatchedBrowserEvent('open');

        $this->assertEquals($maximum, Reservation::count());
    }

    /** @test */
    function admin_can_reserve_for_users_more_than_the_allowed_maximum()
    {
        $this->setRole('super-admin');

        $john = User::factory()->create();

        $maximum = Mass::maxReservations();
        $this->reserveMaximum($maximum, $john);

        $mass = Mass::factory()->create([
            'start' => now()->startOfMonth()->addDays(2 * ($maximum + 1)),
            'end' => now()->startOfMonth()->addDays(2 * ($maximum + 1))->addHours(2),
        ]);

        Livewire::test(MakeReservation::class)
            ->fireEvent('set:event', $mass->id)
            ->call('toggleUser', $john)
            ->call('save')
            ->assertRedirect();

        $this->assertEquals($maximum + 1, Reservation::count());
    }

    function setRole($roleName, $user=null)
    {
        $user ??= $this->user;

        $user->syncRoles([Role::where('name', $roleName)->first()->id]);
    }

    function reserveMaximum($maximum, $user=null)
    {
        $user ??= $this->user;

        $this->travelTo(now()->startOfMonth());

        for ($i=1; $i<=$maximum; $i++) {
            $mass = Mass::factory()->create([
                'start' => now()->startOfMonth()->addDays(2 * $i),
                'end' => now()->startOfMonth()->addDays(2 * $i)->addHours(2),
            ]);

            Livewire::test(MakeReservation::class)
                ->fireEvent('set:event', $mass->id)
                ->call('removeUser', $user->id)
                ->call('toggleUser', $user)
                ->call('save')
                ->assertRedirect();

            $this->assertEquals($i, Reservation::count());
        }
    }
}