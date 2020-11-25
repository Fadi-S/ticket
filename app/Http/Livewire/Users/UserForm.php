<?php

namespace App\Http\Livewire\Users;

use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Component;

class UserForm extends Component
{
    public User $user;
    public bool $create;

    public $password;

    protected $rules = [
        'user.name' => 'required',
        'user.username' => 'required|unique:users,username',
        'user.email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
    ];

    public function mount()
    {
        $this->create = !isset($this->user);

        $this->user = $this->user ?? new User();

        if(!$this->create) {
            $this->rules = [
                'user.name' => 'required',
                'password' => 'nullable|min:6',
                'user.username' => [
                    'required',
                    Rule::unique('users')->ignore($this->user->id),
                ],
                'user.email' => [
                    'required',
                    Rule::unique('users')->ignore($this->user->id),
                ],
            ];
        }
    }

    public function render()
    {
        return view('livewire.users.user-form')->layout('components.master');
    }

    public function updatedUserName($name)
    {
        $this->user->username = User::makeSlug($name, $this->user->id);
    }

    public function save()
    {
        $this->validate($this->rules);

        if($this->password)
            $this->user->password = $this->password;

        $this->user->save();

        if($this->create) {
            $this->user->assignRole("user");

            $this->user = new User();
            $this->password = null;
        }

        session()->flash('success', 'User Saved Successfully');
    }
}
