<?php

namespace App\Http\Livewire\Users;

use App\Models\User\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserForm extends Component
{
    use AuthorizesRequests;

    public User $user;
    public string $password = '';
    public int $role_id = 1;
    public bool $isCreate = true;
    public int $gender = 1;

    public bool $redirect = false;

    protected $queryString = [
        'redirect' => ['except' => false],
    ];
    
    public function mount()
    {
        $this->isCreate = !isset($this->user);

        $this->user ??= new User();

        $this->role_id = (!$this->isCreate) ? $this->user->roles[0]->id : 1;

        $this->gender = (!$this->isCreate) ? $this->user->gender : 1;
    }

    public function render()
    {
        return view('livewire.users.user-form', [
            'roles' => Role::pluck('name', 'id'),
        ])->layout('components.master');
    }

    public function updatedUserName($name) { $this->user->username = User::makeSlug($name, $this->user->id); }

    public function updatedGender($gender)
    {
        $this->validateOnly('gender');

        $this->user->gender = (bool) $gender;
    }

    public function save()
    {
        $this->authorize($this->isCreate ? 'create' : 'update', $this->user);

        $this->validate();

        $this->user->gender = (bool) $this->gender;

        if($this->password)
            $this->user->password = $this->password;

        $this->user->save();

        $this->user->syncRoles([(auth()->user()->isAdmin()) ? $this->role_id : 1]);

        $this->clearFields();

        session()->flash('success', 'User Saved Successfully');

        if($this->redirect)
            $this->dispatchBrowserEvent('closeTab');
    }

    protected function clearFields()
    {
        if($this->isCreate) {
            $this->user = new User();
            $this->password = '';
        }
    }

    protected function rules()
    {
        $rules = [
            'user.name' => 'required',
            'user.username' => 'required|unique:users,username',
            'user.email' => 'required|email|unique:users,email',
            'gender' => 'required|in:0,1',
            'password' => 'required|min:6',
            'role_id' => 'required|exists:roles,id',
        ];

        if(!$this->isCreate) {
            $id = isset($this->user) ? $this->user->id : 0;

            $rules['password'] = 'nullable|min:6';
            $rules['user.username'] =  [
                'required',
                Rule::unique('users', 'username')->ignore($id),
            ];
            $rules['user.email'] = [
                'required',
                Rule::unique('users', 'email')->ignore($id),
            ];
        }

        return $rules;
    }
}
