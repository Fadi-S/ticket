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

        if (!$this->isCreate)
            $this->authorize('update', $this->user);

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

    public function updatedUserName($name)
    {
        if ($this->isCreate)
            $this->user->username = User::makeSlug($name, $this->user->id);
    }

    public function updatedGender($gender)
    {
        $this->validateOnly('gender');

        $this->user->gender = (bool)$gender;
    }

    public function save()
    {
        if (!$this->user->isSignedIn())
            $this->authorize($this->isCreate ? 'create' : 'update', $this->user);

        $this->validate();

        $this->user->gender = (bool)$this->gender;

        if (auth()->user()->isAdmin() && $this->password)
            $this->user->password = $this->password;

        $this->user->save();

        $this->user->syncRoles([(auth()->user()->isAdmin()) ? $this->role_id : 1]);

        if (auth()->user()->isUser() && !$this->user->isSignedIn())
            auth()->user()->addFriend($this->user, true);

        $this->clearFields();

        session()->flash('success', 'User Saved Successfully');

        if ($this->redirect)
            $this->dispatchBrowserEvent('closeTab');
    }

    protected function clearFields()
    {
        if ($this->isCreate) {
            $this->user = new User();
            $this->password = '';
        }
    }

    protected function rules()
    {
        $rules = [
            'user.name' => 'required',
            'user.username' => 'required|unique:users,username',
            'user.email' => [
                'nullable',
                'email',
                'unique:users,email',
            ],
            'user.phone' => [
                'nullable',
                'regex:/((\+2|2)?01)[0-9]{9}/',
                'unique:users,phone',
            ],
            'user.national_id' => [
                'nullable',
                'regex:/(3|2)[0-9]{13}/',
                'unique:users,national_id',
            ],
            'gender' => 'required|in:0,1',
            'password' => [
                Rule::requiredIf( $this->role_id != 1),
                'min:6',
            ],
            'role_id' => 'required|exists:roles,id',
        ];

        if (!$this->isCreate) {
            $id = isset($this->user) ? $this->user->id : 0;

            $rules['password'] = 'nullable|min:6';
            $rules['user.username'] = [
                Rule::requiredIf($this->role_id != 1),
                Rule::unique('users', 'username')->ignore($id),
            ];
            $rules['user.email'] = [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($id),
            ];

            $rules['user.phone'] = [
                'nullable',
                'regex:/((\+2|2)?01)[0-9]{9}/',
                Rule::unique('users', 'phone')->ignore($id),
            ];

            $rules['user.national_id'] = [
                'nullable',
                'regex:/(3|2)[0-9]{13}/',
                Rule::unique('users', 'national_id')->ignore($id),
            ];
        }

        return $rules;
    }
}
