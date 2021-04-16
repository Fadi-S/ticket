<?php

namespace App\Http\Livewire;

use App\Helpers\GoogleAPI;
use App\Helpers\NormalizePhoneNumber;
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

    private string $phoneKey = 'reset.phone';
    private string $stateKey = 'reset.state';

    public function mount()
    {
        if(!session()->has($this->stateKey) || !session($this->phoneKey))
            session()->put($this->stateKey, 0);
    }

    public function render()
    {
        return view('livewire.reset-password-by-phone', [
            'state' => session($this->stateKey),
        ])->layout('components.layouts.auth');
    }

    public function send()
    {
        $this->validate([
            'phone' => 'required'
        ]);

        $this->phone = NormalizePhoneNumber::create($this->phone)->handle();

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

        session()->put($this->phoneKey, $this->phone);
        session()->put($this->stateKey, 1);

        session()->flash('status', "Verification code sent to $this->phone");
    }

    public function confirm()
    {
        if (session($this->stateKey) !== 1)
            return;

        $api = new GoogleAPI();

        $sessionInfo = \DB::table('phone_verifications')
            ->where('phone', session($this->phoneKey))
            ->first()
            ->reCaptcha;

        if (!$api->verifyCode($this->code, $sessionInfo)) {
            session()->flash('error', "Wrong code");

            return;
        }

        \DB::table('phone_verifications')
            ->where('phone', session($this->phoneKey))
            ->delete();

        session()->flash('status', "Success");

        session()->put($this->stateKey, 2);
    }

    public function resetPassword()
    {
        if (session($this->stateKey) !== 2)
            return;

        $this->validate([
            'password' => [
                'min:6',
                'confirmed',
            ]
        ]);

        $user = User::where('phone', session($this->phoneKey))->first();

        $user->password = $this->password;
        if(!$user->isVerified())
            $user->verified_at = now();
        $user->save();

        auth()->login($user, true);

        session()->forget($this->stateKey);
        session()->forget($this->phoneKey);

        flash()->success('Password Reset Successfully');

        $this->redirect('/');
    }
}
