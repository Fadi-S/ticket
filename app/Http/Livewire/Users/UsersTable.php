<?php

namespace App\Http\Livewire\Users;

use App\Models\User\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class UsersTable extends Component
{
    use AuthorizesRequests, WithPagination;

    public string $search = '';

    protected $queryString = [
        'search' => ['except' => '']
    ];

    public function mount()
    {
        $this->authorize('viewAny', User::class);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.users.users-table', [
            'users' => User::searchDatabase($this->search)->paginate(15),
        ])->layout('components.master');
    }
}
