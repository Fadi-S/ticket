<?php

namespace Tests\Feature;

use App\Helpers\NormalizePhoneNumber;
use App\Models\Location;
use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
   use DatabaseMigrations;

   protected function setUp(): void
   {
       parent::setUp();

       $this->seed();

       config(['settings.allow_users_to_create_accounts' => true]);
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
    function a_user_can_login_using_username()
    {
        User::factory()->create([
            'username' => 'test.user',
            'password' => '123456',
        ]);

        $this->post('/login', [
            'email' => 'test.user',
            'password' => '123456',
        ])->assertRedirect('/');
    }

    /** @test */
    function a_user_can_login_using_phone()
    {
        User::factory()->create([
            'phone' => '01200000000',
            'password' => '123456',
        ]);

        $this->post('/login', [
            'email' => '01200000000',
            'password' => '123456',
        ])->assertRedirect('/');
        auth()->logout();

        $this->post('/login', [
            'email' => '201200000000',
            'password' => '123456',
        ])->assertRedirect('/');
        auth()->logout();

        $this->post('/login', [
            'email' => '+201200000000',
            'password' => '123456',
        ])->assertRedirect('/');
        auth()->logout();
    }

    /** @test */
    function phone_must_be_unique_in_registration()
    {
        User::factory()->create(['phone' => '01200000000']);

        $this->registerUser(['phone' => '01200000000'])
            ->assertSessionHasErrors('phone');
    }

    /** @test */
    function username_must_be_unique_in_registration()
    {
        $this->registerUser(['name' => 'Fadi Sarwat Farouk'])
            ->assertRedirect('/');

        auth()->logout();

        $this->registerUser(['name' => 'Fadi Sarwat Farouk', 'email' => 'test1@alsharobim.com', 'phone' => '01200000001'])
            ->assertSessionDoesntHaveErrors();

        auth()->logout();

        $this->registerUser(['name' => 'Fadi Sarwat Farouk', 'email' => 'test2@alsharobim.com', 'phone' => '01200000002'])
            ->assertSessionDoesntHaveErrors();

        // dump(User::find(4));
    }

    /** @test */
    function generated_username_is_unique()
    {
        $this->registerUser([
            'name' => 'Test User Name',
            'email' => 'test1@example.com',
            'phone' => '01200000000',
        ]);

        $user = User::find(2);

        $this->assertEquals('test.user.name', $user->username);

        auth()->logout();

        $this->registerUser([
            'name' => 'Test User Name',
            'email' => 'test2@example.com',
            'phone' => '01200000001',
        ]);

        $user = User::find(3);

        $this->assertEquals('test.user.name.1', $user->username);
    }

    /** @test */
    function invalid_phone_number_doesnt_break_code()
    {
        $this->post('/login', [
            'email' => '46821684165498156486',
            'password' => '123456',
        ])->assertSessionHasErrors();

        $this->post('/login', [
            'email' => '42',
            'password' => '123456',
        ])->assertSessionHasErrors();
    }

    /** @test */
    function user_cant_login_with_wrong_password()
    {
        User::factory()->create([
            'email' => 'test@alsharobim.com',
            'password' => '123456',
        ]);

        $this->post('/login', [
            'email' => 'test@alsharobim.com',
            'password' => '1234565',
        ])->assertSessionHasErrors();
    }

    /** @test */
    function a_user_can_register()
    {
        $this->assertEquals(1, User::count());

        $this->registerUser()
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect('/');

        $this->assertEquals(2, User::count());
    }

    /** @test */
    function creating_user_with_same_data_twice()
    {
        $this->registerUser()
            ->assertSessionDoesntHaveErrors();
        $this->assertEquals(2, User::count());

        auth()->logout();

        $this->registerUser()
            ->assertSessionHasErrors();

        $this->assertEquals(2, User::count());
    }

    /** @test */
    function user_can_write_phone_number_in_arabic()
    {
        $this->registerUser(['phone' => '٠١٢٧٣٣١٥٨٧٠']);

        $user = User::find(2);

        $this->assertEquals('+201273315870', $user->phone);
    }

    /** @test */
    function user_can_write_phone_number_in_english()
    {
        $this->registerUser(['phone' => '01273315870']);

        $user = User::find(2);

        $this->assertEquals('+201273315870', $user->phone);
    }

    /** @test */
    function email_can_be_empty()
    {
        $this->registerUser([
            'email' => null,
        ])->assertRedirect('/');

        $this->assertEquals(2, User::count());

        auth()->logout();

        $this->registerUser([
            'email' => null,
            'phone' => '01273315871',
        ])
            ->assertRedirect('/');

        $this->assertEquals(3, User::count());
    }

    /** @test */
    function email_must_be_unique()
    {
        $this->registerUser(['email' => 'test@alsharobim.com'])
            ->assertRedirect('/');

        auth()->logout();

        $this->registerUser(['email' => 'test@alsharobim.com'])
            ->assertSessionHasErrors('email');

        $this->assertEquals(2, User::count());
    }

    /** @test */
    function national_id_must_be_valid()
    {
        $this->registerUser(['national_id' => '55464891455458'])
             ->assertSessionHasErrors('national_id');

        $this->registerUser(['national_id' => '35464891455874'])
            ->assertSessionHasErrors('national_id');

        $this->registerUser(['national_id' => '25464891455984'])
            ->assertSessionHasErrors('national_id');
    }

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
     * @param $value
     */
    function some_fields_must_be_unique($field, $unique, $value=null)
    {
        $value = $value ?? 'notUnique@email.com';
        User::factory()->create([
            $field => $value
        ]);

        $response = $this->registerUser([$field => $value]);

        if($unique == 'unique')
            $response->assertSessionHasErrors($field);
        else
            $response->assertRedirect('/');

        $this->assertEquals(($unique == 'unique') ? 2 : 3, User::count());
    }

    function registerUser($attributes=[])
    {
        return $this->post('/register', array_merge([
            'name' => 'John Doe Third',
            'arabic_name' => 'جون دو اسم',
            'email' => 'test@example.com',
            //'national_id' => '30204250201612',
            'password' => '123456',
            'gender' => 1,
            'password_confirmation' => '123456',
            'phone' => '01273315870',
            'location_id' => 1,
        ], $attributes));
    }

    public function fields()
    {
        return [
            ['phone', 'unique', '01200000000'],
            ['name', 'not_unique', 'John Doe Third'],
            ['arabic_name', 'not_unique', 'جون دو اسم'],
        ];
    }
}
