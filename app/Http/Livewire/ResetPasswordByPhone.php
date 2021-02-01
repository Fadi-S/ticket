<?php

namespace App\Http\Livewire;

use App\Helpers\GoogleAPI;
use App\Models\User\User;
use Livewire\Component;

class ResetPasswordByPhone extends Component
{
    public $phone;
    public $code;
    public $reCaptcha;

    public $password;
    public $password_confirmation;

    protected $messages = [
        'phone.exists' => 'There is no user with this phone number.',
        'phone.regex' => 'This phone number is invalid.',
    ];

    public function mount()
    {
        if(!session()->has('reset.state'))
            session()->put('reset.state', 0);

        $this->phone = session('phone');
    }

    public function render()
    {
        return view('livewire.reset-password-by-phone', [
            'state' => session('reset.state'),
        ])->layout('components.layouts.auth');
    }

    public function sendCode()
    {
        $this->validate([
            'phone' => 'required'
        ]);

        preg_match('/(?P<number>(01)[0-9]{9})/', $this->phone, $matches);

        if(isset($matches['number']))
            $this->phone = '+2' . $matches['number'];

        $this->validate([
            'phone' => [
                'required',
                'regex:/(\+201)[0-9]{9}/',
                'exists:users',
            ]
        ]);

        $api = new GoogleAPI();

        $response = $api->sendVerificationCode($this->phone, $this->reCaptcha);

        if(!isset($response['sessionInfo'])) {
            session()->flash('error', 'An error has occurred');

            return;
        }

        \DB::table('phone_verifications')
            ->where('phone', $this->phone)
            ->delete();

        \DB::table('phone_verifications')->insert([
            'phone' => $this->phone,
            'reCaptcha' => $response['sessionInfo'],
        ]);

        session()->put('phone', $this->phone);
        session()->put('reset.state', 1);

        session()->flash('status', "Verification code sent to $this->phone");
    }

    public function confirmCode()
    {
        if (session('reset.state') !== 1)
            return;

        $api = new GoogleAPI();

        $sessionInfo = \DB::table('phone_verifications')
            ->where('phone', $this->phone)
            ->first()
            ->reCaptcha;

        if (!$api->verifyCode($this->code, $sessionInfo)) {
            session()->flash('error', "Wrong code");

            return;
        }

        \DB::table('phone_verifications')
            ->where('phone', $this->phone)
            ->delete();

        session()->remove('phone');

        session()->flash('status', "Success");

        session()->put('reset.state', 2);
    }

    public function resetPassword()
    {
        if (session('reset.state') !== 2)
            return;

        $this->validate([
            'password' => [
                'min:6',
                'confirmed',
            ]
        ]);

        $user = User::where('phone', $this->phone)->first();

        $user->password = $this->password;

        auth()->login($user, true);

        session()->forget('reset.state');

        flash()->success('Password Reset Successfully');

        $this->redirect('/');
    }
}
