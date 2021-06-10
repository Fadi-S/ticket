<?php

namespace App\Http\Livewire;

use App\Helpers\GoogleAPI;
use App\Models\User\User;
use Illuminate\Support\Facades\Redis;
use Livewire\Component;

class VerifyPhoneNumber extends Component
{
    public bool $sent = false;
    public $reCaptcha;
    public bool $edit = false;

    public User $user;

    public $code;

    public bool $independent = true;

    protected $listeners = [
        'user-created' => 'editFinish'
    ];

    public function mount()
    {
        $this->user ??= auth()->user();
    }

    public function render()
    {
        if($this->independent) {
            return view('livewire.verify-phone-number')
                ->layout('components.layouts.auth');
        }

        return view('livewire.verify-phone-number');
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

        $phone = $this->user->phone;

        $api = new GoogleAPI();

        $response = $api->sendVerificationCode($phone, $this->reCaptcha);

        if(!isset($response['sessionInfo'])) {
            $error = collect($response['error']);

            session()->flash('error', __('google.' . $error->get('message')));

            $failures = collect(json_decode(Redis::hGet('verification_failures', $this->user->id)));

            $error = $error->put('user_id', $this->user->id);
            $error = $error->put('phone', $this->user->phone);

            $failures->push($error);
            Redis::hSet('verification_failures', $this->user->id, $failures->toJson());

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
        $user = $this->user;
        $api = new GoogleAPI();

        $sessionInfo = \DB::table('phone_verifications')
            ->where('phone', $user->phone)
            ->first();
        if($sessionInfo) {
            $sessionInfo = $sessionInfo->reCaptcha;
        }else {
            session()->flash('error', __("Couldn't find your phone number, try sending the code again"));
            return;
        }

        if (!$api->verifyCode($this->code, $sessionInfo)) {
            session()->flash('error', "Wrong code");

            return;
        }

        \DB::table('phone_verifications')
            ->where('phone', $user->phone)
            ->delete();

        $user->verified_at = now();
        $user->save();

        $this->emit('user-verified', $user->username);

        session()->flash('status', __('Verified'));

        if($this->independent) {
            flash()->success(__('Verified'));

            $this->redirect('/');
        }
    }
}
