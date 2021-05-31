<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
use App\Helpers\NormalizePhoneNumber;
use App\Helpers\StandardRegex;
use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Login;
use App\Providers\RouteServiceProvider;
use App\Models\User\User;
use App\Rules\ArabicOnly;
use App\Rules\EnglishOnly;
use App\Rules\Fullname;
use App\Rules\NationalIDValidation;
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


    public function showRegistrationForm()
    {
        $locations = collect([0 => 'المنطقة']);
        $locs = Location::pluck('name', 'id');
        foreach ($locs as $key => $location)
            $locations->put($key, $location);

        return view('auth.register', [
            'locations' => $locations,
        ]);
    }


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
        $data['phone'] = NormalizePhoneNumber::create($data['phone'] ?? '')->handle();
        $data['username'] = User::makeSlug(config('settings.arabic_name_only') ? $data['arabic_name'] : $data['name']);
        \request()->request->set('username', $data['username']);

        return Validator::make($data, [
            'name' => [(config('settings.arabic_name_only')) ? 'nullable' : 'required', 'string', 'max:191', new Fullname, new EnglishOnly],
            'arabic_name' => ['required', 'string', 'max:191', new Fullname, new ArabicOnly],
            'email' => ['nullable', 'string', 'email', 'max:191', 'unique:users'],
            'phone' => ['required', 'string', 'regex:/' . StandardRegex::PHONE_NUMBER . '/', 'unique:users'],
            'username' => ['required', 'unique:users'],
            'national_id' => ['nullable', 'regex:/' . StandardRegex::NATIONAL_ID . '/', 'unique:users', new NationalIDValidation,],
            'password' => [(config('settings.national_id_required')) ? 'nullable' : 'required', 'string', 'min:' . User::$minPassword, 'confirmed'],
            'gender' => ['required', Rule::in([1, 0]),],
            'location_id' => ['required', 'exists:locations,id',],
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
            'location_id' => $data['location_id'],
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
