<?php

namespace App\Http\Livewire\Users;

use App\Models\User\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class UsersTable extends Component
{
    use AuthorizesRequests;

    public function mount()
    {
        $this->authorize('viewAny', User::class);
    }

    public function render()
    {
        return view('livewire.users.users-table');
    }
}
