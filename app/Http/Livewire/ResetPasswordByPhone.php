<?php

namespace App\Http\Livewire;

use App\Helpers\GoogleAPI;
use App\Models\User\User;
use Livewire\Component;

class ResetPasswordByPhone extends Component
{
    public $phone;
    public $code;
    public int $state = 0;

    public $password;
    public $password_confirmation;

    public function mount()
    {
        if(!session()->has('phone')) {
            $this->redirect('/password/phone');

            return;
        }

        $this->phone = session('phone');
    }

    public function render()
    {
        return view('livewire.reset-password-by-phone')
            ->layout('components.layouts.auth');
    }

    public function confirmCode()
    {
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

        session()->put('authenticated', true);

        $this->state = 1;
    }

    public function resetPassword()
    {
        if (!session('authenticated')) {
            $this->state = 0;

            return;
        }

        $this->validate([
            'password' => [
                'min:6',
                'confirmed',
            ]
        ]);

        $user = User::where('phone', $this->phone)->first();

        $user->password = $this->password;

        auth()->login($user, true);

        session()->forget('authenticated');

        $this->redirect('/');
    }
}
