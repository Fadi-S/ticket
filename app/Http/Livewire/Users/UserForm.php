<?php

namespace App\Http\Livewire\Users;

use App\Helpers\StandardRegex;
use App\Models\User\User;
use App\Rules\ArabicOnly;
use App\Rules\EnglishOnly;
use App\Rules\Fullname;
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

    public bool $card = true;

    public array $only = [];

    public function mount()
    {
        $this->isCreate = !isset($this->user);

        if (!$this->isCreate)
            $this->authorize('update', $this->user);

        $this->user ??= new User();

        $this->role_id = (!$this->isCreate && isset($this->user->roles[0])) ? $this->user->roles[0]->id : 1;

        $this->gender = (!$this->isCreate) ? $this->user->gender : 1;
    }

    public function render()
    {
        return view('livewire.users.user-form', [
            'roles' => Role::pluck('name', 'id'),
        ])->layout('components.master');
    }

    public function updatedGender($gender)
    {
        $this->validateOnly('gender');

        $this->user->gender = (bool)$gender;
    }

    public function updated($field, $value)
    {
        $this->validateOnly($field);
    }

    public function save()
    {
        if (!$this->user->isSignedIn())
            $this->authorize($this->isCreate ? 'create' : 'update', $this->user);

        $this->validate();

        $this->user->gender = (bool)$this->gender;

        if(auth()->user()->can('users.edit')) {
            if ($this->password) $this->user->password = $this->password;
        }

        if($this->isCreate) {
            $this->user->username = User::makeSlug($this->user->name, $this->user->id);
        }

        if(!$this->user->isSignedIn() && auth()->user()->isUser()) {
            $this->user->creator_id = auth()->id();
        }

        $this->user->save();
        $this->user->syncRoles([(auth()->user()->can('editRole')) ? $this->role_id : 1]);

        if (auth()->user()->isUser() && !$this->user->isSignedIn())
            auth()->user()->forceAddFriend($this->user);

        $this->emit('user-created', $this->user->username);

        if($this->isCreate) {
            session()->flash('success', __('User Created Successfully with id #:id', ['id' => $this->user->id]));
        }else {
            session()->flash('success', __('User Saved Successfully'));
        }

        $this->clearFields();
    }

    public function delete()
    {
        if($this->isCreate && !auth()->user()->can('users.delete'))
            return;

        $this->user->reservations->each->cancel();

        $this->user->logins()->delete();
        $this->user->forceDelete();

        flash()->success('User Deleted Successfully');

        $this->redirect('/users');
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
            'user.name' => [
                'required',
                new Fullname,
                new EnglishOnly
            ],
            'user.arabic_name' => [
                'required',
                new Fullname,
                new ArabicOnly
            ],
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
            'user.national_id' => [
                'nullable',
                'regex:/' . StandardRegex::NATIONAL_ID . '/',
                'unique:users,national_id',
            ],
            'gender' => 'required|in:0,1',
            'password' => [
                'nullable',
                'min:' . User::$minPassword,
            ],
            'role_id' => 'required|exists:roles,id',
        ];

        if (!$this->isCreate) {
            $id = isset($this->user) ? $this->user->id : 0;

            $rules['password'] = 'nullable|min:' . User::$minPassword;
            $rules['user.email'] = [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($id),
            ];

            $rules['user.phone'] = [
                Rule::requiredIf($this->showField('user.phone')),
                'regex:/' . StandardRegex::PHONE_NUMBER . '/',
                Rule::unique('users', 'phone')->ignore($id),
            ];

            $rules['user.national_id'] = [
                'nullable',
                'regex:/' . StandardRegex::NATIONAL_ID . '/',
                Rule::unique('users', 'national_id')->ignore($id),
            ];
        }

        foreach ($rules as $field => $rule) {
            if(!$this->showField($field))
                $rules[$field] = [];
        }

        return $rules;
    }

    public function showField($field) : bool
    {
        return !count($this->only) || in_array($field, $this->only);
    }
}
