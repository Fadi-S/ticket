<?php

namespace App\Http\Livewire;

use App\Helpers\GoogleAPI;
use Livewire\Component;

class VerifyPhoneNumber extends Component
{
    public bool $sent = false;
    public $reCaptcha;
    public bool $edit = false;

    public $code;

    protected $listeners = [
        'user-created' => 'editFinish'
    ];

    public function render()
    {
        return view('livewire.verify-phone-number')
            ->layout('components.master');
    }

    public function editFinish()
    {
        $this->edit = false;
    }

    public function send()
    {
        if(time() - session('sent_at') < 60) {
            return;
        }

        $phone = auth()->user()->phone;

        $api = new GoogleAPI();

        $response = $api->sendVerificationCode($phone, $this->reCaptcha);

        if(!isset($response['sessionInfo'])) {
            session()->flash('error', 'An error has occurred');

            return;
        }

        \DB::table('phone_verifications')
            ->where('phone', $phone)
            ->delete();

        \DB::table('phone_verifications')->insert([
            'phone' => $phone,
            'reCaptcha' => $response['sessionInfo'],
        ]);

        session()->put('sent_at', time());
        $this->sent = true;

        session()->flash('status', "Verification code sent to $phone");
    }

    public function verify()
    {
        $user = auth()->user();
        $api = new GoogleAPI();

        $sessionInfo = \DB::table('phone_verifications')
            ->where('phone', $user->phone)
            ->first()
            ->reCaptcha;

        if (!$api->verifyCode($this->code, $sessionInfo)) {
            session()->flash('error', "Wrong code");

            return;
        }

        \DB::table('phone_verifications')
            ->where('phone', $user->phone)
            ->delete();

        $user->verified_at = now();
        $user->save();

        flash()->success("Success");

        $this->redirect('/');
    }
}
