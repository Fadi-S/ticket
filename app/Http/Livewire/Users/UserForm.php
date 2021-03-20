<?php

namespace App\Http\Livewire\Users;

use App\Helpers\StandardRegex;
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
    public string $tempUsername = '';
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

        $this->role_id = (!$this->isCreate && isset($this->user->roles[0])) ? $this->user->roles[0]->id : 1;

        $this->gender = (!$this->isCreate) ? $this->user->gender : 1;

        $this->tempUsername = (!$this->isCreate) ? $this->user->username : '';
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
            $this->tempUsername = User::makeSlug($name, $this->user->id);
    }

    public function updatedTempUsername($username)
    {
        $this->tempUsername = User::replaceInvalidCharacters($username);
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

        $this->user->username = User::replaceInvalidCharacters($this->tempUsername);

        $this->user->save();

        $this->user->syncRoles([(auth()->user()->isAdmin()) ? $this->role_id : 1]);

        if (auth()->user()->isUser() && !$this->user->isSignedIn())
            auth()->user()->forceAddFriend($this->user);

        $this->emit('user-created', $this->user->username);

        $this->clearFields();

        session()->flash('success', __('User Saved Successfully'));


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
            'user.arabic_name' => 'required',
            'tempUsername' => 'required|unique:users,username',
            'user.email' => [
                'nullable',
                'email',
                'unique:users,email',
            ],
            'user.phone' => [
                'required',
                'regex:/' . StandardRegex::PHONE_NUMBER . '/',
                'unique:users,phone',
            ],
            /*'user.national_id' => [
                'nullable',
                'regex:/' . StandardRegex::NATIONAL_ID . '/',
                'unique:users,national_id',
            ],*/
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
            $rules['tempUsername'] = [
                'required',
                Rule::unique('users', 'username')->ignore($id),
            ];
            $rules['user.email'] = [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($id),
            ];

            $rules['user.phone'] = [
                'required',
                'regex:/' . StandardRegex::PHONE_NUMBER . '/',
                Rule::unique('users', 'phone')->ignore($id),
            ];

            /*$rules['user.national_id'] = [
                'nullable',
                'regex:/' . StandardRegex::NATIONAL_ID . '/',
                Rule::unique('users', 'national_id')->ignore($id),
            ];*/
        }

        return $rules;
    }
}
