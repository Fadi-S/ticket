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
    public int $role_id = 3;

    public bool $isCreate = true;
    public bool $isAdmin = false;
    
    public function mount()
    {
        $this->isCreate = !isset($this->user);

        $this->isAdmin = explode('/', request()->path())[0] == 'admins';

        $this->user ??= new User();

        $this->role_id = (!$this->isCreate && $this->isAdmin)
            ? $this->user->roles[0]->id : 3;
    }

    public function render()
    {
        return view('livewire.users.user-form', [
            'roles' => $this->getRoles(),
        ])->layout('components.master');
    }

    public function updatedUserName($name) { $this->user->username = User::makeSlug($name, $this->user->id); }

    public function save()
    {
        $this->authorize($this->isCreate ? 'create' : 'update', $this->user);

        $this->validate();

        if($this->password)
            $this->user->password = $this->password;

        $this->user->save();

        $this->assignRole();

        $this->clearFields();

        session()->flash('success', 'User Saved Successfully');
    }

    protected function clearFields()
    {
        if($this->isCreate) {
            $this->user = new User();
            $this->password = '';
        }
    }

    protected function assignRole()
    {
        if($this->isAdmin)
            $this->user->syncRoles([$this->role_id]);
        else if($this->isCreate)
            $this->user->assignRole("user");
    }

    protected function getRoles()
    {
        return $this->isAdmin ?
            Role::where("name", "<>", "user")->pluck('name', 'id')
            : null;
    }

    protected function rules()
    {
        $rules = [
            'user.name' => 'required',
            'user.username' => 'required|unique:users,username',
            'user.email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ];

        if($this->isAdmin)
            $rules['role_id'] = 'required|exists:roles,id';

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
