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
    public bool $searchByScout = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'searchByScout' => ['except' => false],
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
            'users' => $this->getUsers(),
        ])->layout('components.master');
    }

    private function getUsers()
    {
        $method = ($this->searchByScout) ? 'search' : 'searchDatabase';

        return User::$method($this->search)
            ->with('creator')
            ->paginate(15);
    }
}
