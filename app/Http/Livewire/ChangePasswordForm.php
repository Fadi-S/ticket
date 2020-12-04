<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ChangePasswordForm extends Component
{

    public bool $shown = false;

    public string $old_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    protected $listeners = [
        'hide' => 'hide',
    ];

    protected $rules = [
        'old_password' => 'required|password',
        'new_password' => 'required|min:6|confirmed',
    ];

    public function render()
    {
        return view('livewire.change-password-form');
    }

    public function change()
    {
        $this->validate();

        $user = auth()->user();

        $user->password = $this->new_password;

        $user->save();

        session()->flash('success', 'Password Changed Successfully');

        $this->clearForm();

        $this->emitSelf('hide');
    }

    public function clearForm()
    {
        $this->old_password = '';
        $this->new_password = '';
        $this->new_password_confirmation = '';
    }

    public function show()
    {
        $this->shown = true;
    }

    public function hide()
    {
        sleep(1);

        $this->shown = false;
    }
}
