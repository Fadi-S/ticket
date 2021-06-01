<?php

namespace App\Http\Livewire;

use App\Models\User\User;
use App\Rules\ArabicOnly;
use App\Rules\EnglishOnly;
use App\Rules\Fullname;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class AddGuest extends Component
{
    use AuthorizesRequests;

    public bool $gender = true;
    public User $user;
    public $date;

    public function mount()
    {
        $this->user = new User;
    }

    public function render()
    {
        return view('livewire.add-guest');
    }

    public function updated()
    {
        $this->validate();
    }

    public function save()
    {
        $this->authorize('createGuest');
        $this->validate();

        $this->user->gender = $this->gender;
        $this->user->username = User::makeSlug($this->user->arabic_name);
        $this->user->name = $this->user->arabic_name;
        $this->user->expiration = Carbon::parse($this->date)->endOfDay();
        $this->user->save();

        $this->emit('user-created', $this->user->username);
        session()->flash('success', __('Guest added successfully'));

        $this->clearFields();

        $this->dispatchBrowserEvent('closeguest');
    }

    private function clearFields()
    {
        $this->user = new User;
        $this->gender = true;
        $this->date = null;
    }

    public function rules()
    {
        $rules = [
            'user.arabic_name' => [
                'required',
                new Fullname,
                new ArabicOnly
            ],
            'gender' => 'required|boolean',
        ];

        if(config('settings.arabic_name_only')) {
            $rules['user.name'] = ['nullable'];
        }

        return $rules;
    }
}
