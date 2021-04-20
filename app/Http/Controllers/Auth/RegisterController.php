<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
use App\Helpers\NormalizePhoneNumber;
use App\Helpers\StandardRegex;
use App\Http\Controllers\Controller;
use App\Models\Login;
use App\Providers\RouteServiceProvider;
use App\Models\User\User;
use App\Rules\ArabicOnly;
use App\Rules\EnglishOnly;
use App\Rules\Fullname;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $data['phone'] = NormalizePhoneNumber::create($data['phone'])->handle();
        $data['username'] = User::makeSlug($data['name']);
        \request()->request->set('username', $data['username']);

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:191', new Fullname, new EnglishOnly],
            'arabic_name' => ['required', 'string', 'max:191', new Fullname, new ArabicOnly],
            'email' => ['nullable', 'string', 'email', 'max:191', 'unique:users'],
            'phone' => ['required', 'string', 'regex:/' . StandardRegex::PHONE_NUMBER . '/', 'unique:users'],
            'username' => ['required', 'unique:users'],
            'national_id' => ['nullable', 'regex:/' . StandardRegex::NATIONAL_ID . '/', 'unique:users'],
            'password' => ['required', 'string', 'min:' . User::$minPassword, 'confirmed'],
            'gender' => ['required', Rule::in([1, 0]),],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'arabic_name' => $data['arabic_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'gender' => $data['gender'],
            'national_id' => $data['national_id'] ?? null,
            'username' => $data['username'],
            'password' => $data['password'],
        ]);
    }

    protected function registered(Request $request, $user)
    {
        flash()->success('Registration completed successfully, your ID is: ' . $user->id);

        $user->assignRole('user');

        Login::saveCurrentSession();

        UserRegistered::dispatch();
    }
}
