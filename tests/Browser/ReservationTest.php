<?php

namespace Tests\Browser;

use App\Http\Livewire\MakeReservation;
use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Livewire\Livewire;
use Tests\DuskTestCase;

class ReservationTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    /** @test */
    function it_loads_component()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->get('/reserve')
            ->assertSee('Search Users');
    }

    /** @test */
    function can_search_for_friends()
    {
        $this->browse(function (Browser $browser) {

            $user = User::factory()->create();

            $fadi = User::factory()->create(['name' => 'Hello World', 'username' => 'fadisarwat']);

            $user->addFriend($fadi, true);

            $browser->loginAs($user)
                ->visit('/reserve')
                ->assertDontSee('Create New User')

                ->type('user-search', 'Hello')
                ->waitForText($fadi->name)
                ->assertSee($fadi->name)

                ->type('user-search', "~$fadi->id")
                ->waitForText($fadi->name)
                ->assertSee($fadi->name)

                ->type('user-search', "@$fadi->username")
                ->waitForText($fadi->name)
                ->assertSee($fadi->name)

                ->type('user-search', 'fdfsddsfgsdfgf')
                ->waitForText('Create New User')
                ->assertSee('Create New User');
        });

    }

    /** @test */
    function a_user_cant_search_for_non_friends()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();

            User::factory()->count(5)->create();

            $fadi = User::factory()->create(['name' => 'Fadi Sarwat', 'username' => 'fadisarwat']);

            $browser->loginAs($user)
                ->visit('/reserve')

                ->type('user-search', 'Fadi')
                ->waitFor('#open-user-btn')
                ->assertSee('Create New User');
        });
    }

    /** @test */
    function can_open_create_user_model()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();

            $browser->loginAs($user)
                ->visit('/reserve')
                ->type('user-search', 'fdfsddsfgsdfgf')
                ->waitForText('Create New User')
                ->click('#open-user-btn')
                ->waitForText("Name")
                ->assertSeeAnythingIn('#user-form-modal');
        });
    }

    /** @test */
    function can_add_users_to_list()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(MakeReservation::class)
            ->assertDontSee('Create New User')

            ->set('search', '~1')
            ->assertSee($user->name);
    }

    /** @test */
    function can_make_reservation()
    {

    }

    /** @test */
    function an_event_is_required()
    {

    }

    /** @test */
    function users_are_required()
    {

    }

    /** @test */
    function a_user_can_only_reserve_for_friends()
    {

    }
}
