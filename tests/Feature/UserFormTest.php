<?php

namespace Tests\Feature;

use App\Http\Livewire\Users\UserForm;
use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Livewire\Livewire;
use Tests\TestCase;

class UserFormTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    /** @test */
    function a_user_can_create_another_user()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $this->actingAs($user);

        Livewire::test(UserForm::class)
            ->set('user.name', 'John Doe third')
            ->set('user.arabic_name', 'جون دو اسم')
            ->set('user.email', 'test@example.com')
            ->set('user.phone', '01200000000')
            ->set('gender', 1)
            ->call('save')
            ->assertSee(__('User Saved Successfully'));

        $this->assertEquals(3, User::count());
    }

    /** @test */
    function when_creating_another_user_email_can_be_blank()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $this->actingAs($user);

        Livewire::test(UserForm::class)
            ->set('user.name', 'John Doe third third')
            ->set('user.arabic_name', 'بيس جون دو اسم')
            ->set('user.email', '')
            ->set('user.phone', '01200000000')
            ->set('gender', 1)
            ->call('save')
            ->assertSee(__('User Saved Successfully'));

        $this->assertEquals(3, User::count());

        Livewire::test(UserForm::class)
            ->set('user.name', 'John Doe third')
            ->set('user.arabic_name', 'جون دو اسم')
            ->set('user.email', '')
            ->set('user.phone', '01200000001')
            ->set('gender', 1)
            ->call('save')
            ->assertSee(__('User Saved Successfully'));

        $this->assertEquals(4, User::count());
    }

    /** @test */
    function phone_number_must_be_unique()
    {
        $user = User::factory()->create(['phone' => '01200000000']);
        $user->assignRole('user');
        $this->actingAs($user);

        Livewire::test(UserForm::class)
            ->set('user.name', 'John Doe third')
            ->set('user.arabic_name', 'جون دو اسم')
            ->set('user.email', '')
            ->set('user.phone', '01200000000')
            ->set('gender', 1)
            ->call('save')
            ->assertHasErrors('user.phone');

        $this->assertEquals(2, User::count());
    }
}
