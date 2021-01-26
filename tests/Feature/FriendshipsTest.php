<?php

namespace Tests\Feature;

use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class FriendshipsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_user_can_add_friends()
    {
        $john = User::factory()->create(['name' => 'John']);
        $mary = User::factory()->create(['name' => 'Mary']);

        $john->addFriend($mary);

        $this->assertEquals(1, $john->friends(true)->count());
    }

    /** @test */
    function a_user_can_confirm_friends()
    {
        $john = User::factory()->create(['name' => 'John']);
        $mary = User::factory()->create(['name' => 'Mary']);

        $john->addFriend($mary);

        $mary->confirmFriend($john);

        $this->assertEquals(1, $john->friends()->count());
    }

    /** @test */
    function an_unconfirmed_friendship_is_not_counted_in_fiends()
    {
        $john = User::factory()->create(['name' => 'John']);
        $mary = User::factory()->create(['name' => 'Mary']);

        $john->addFriend($mary);

        $this->assertEquals(0, $john->friends()->count());
    }

    /** @test */
    function a_friendship_confirmation_doesnt_confirm_all_friendships()
    {
        $john = User::factory()->create(['name' => 'John']);
        $mary = User::factory()->create(['name' => 'Mary']);

        $sarah = User::factory()->create(['name' => 'Sarah']);
        $fadi = User::factory()->create(['name' => 'Fadi']);

        $fadi->addFriend($sarah);
        $john->addFriend($fadi);
        $mary->addFriend($sarah);
        $john->addFriend($fadi);

        $this->assertEquals(0, $fadi->friends()->count());
        $this->assertEquals(0, $john->friends()->count());
        $this->assertEquals(0, $mary->friends()->count());
        $this->assertEquals(0, $sarah->friends()->count());

        $sarah->confirmFriend($fadi);

        $this->assertEquals(1, $sarah->friends()->count());
        $this->assertEquals(1, $fadi->friends()->count());
        $this->assertEquals(0, $john->friends()->count());
        $this->assertEquals(0, $mary->friends()->count());
    }
}