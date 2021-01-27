<?php

namespace Tests\Feature;

use App\Http\Livewire\Users\UserForm;
use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Livewire\Livewire;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
   use DatabaseMigrations;

   protected function setUp(): void
   {
       parent::setUp();

       $this->seed();
   }

    /** @test */
    function a_user_can_login_using_email()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => '123456',
        ]);

        $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '123456',
        ])->assertRedirect('/');
    }

    /** @test */
    function a_user_can_register()
    {
        $this->registerUser()
            ->assertRedirect('/');

        $this->assertEquals(2, User::count());
    }

    /** @test */
    function a_user_can_create_another_user()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $this->actingAs($user);

        Livewire::test(UserForm::class)
            ->set('user.name', 'John Doe')
            ->assertSet('user.username', User::makeSlug('John Doe'))
            ->set('user.email', 'test@example.com')
            ->set('gender', 1)
            ->call('save')
        ->assertSee('User Saved Successfully');

        $this->assertEquals(3, User::count());
    }

    /** @test */
    /*function a_user_can_claim_an_account_created_by_another_user()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $this->actingAs($user);

        Livewire::test(UserForm::class)
            ->set('user.name', 'John Doe')
            ->assertSet('user.username', User::makeSlug('John Doe'))
            ->set('user.email', 'test@example.com')
            ->set('gender', 1)
            ->call('save')
            ->assertSee('User Saved Successfully');

        $this->assertEquals(3, User::count());

        auth()->logout();

        $this->post('/claim', [
            'email' => 'test@example.com',
        ]);
    }*/

    /**
     * @test
     * @dataProvider fields
     * @param $field
     * @param $unique
     */
    function a_user_must_provide_all_required_fields($field, $unique)
    {
        $this->registerUser([$field => null])
            ->assertSessionHasErrors($field);

        $this->assertEquals(1, User::count());
    }

    /**
     * @test
     * @dataProvider fields
     * @param $field
     * @param $unique
     */
    function some_fields_must_be_unique($field, $unique)
    {
        User::factory()->create([
            $field => 'notUnique@email.com'
        ]);

        $response = $this->registerUser([$field => 'notUnique@email.com']);

        if($unique == 'unique')
            $response->assertSessionHasErrors($field);
        else
            $response->assertRedirect('/');

        $this->assertEquals(($unique == 'unique') ? 2 : 3, User::count());
    }

    function registerUser($attributes=[])
    {
        return $this->post('/register', array_merge([
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'phone' => '01273315870',
            'username' => 'john',
        ], $attributes));
    }

    public function fields()
    {
        return [
            ['email', 'unique'],
            ['phone', 'unique'],
            ['username', 'unique'],
            ['name', 'not_unique'],
        ];
    }
}