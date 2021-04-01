<?php

namespace App\Http\Livewire;

use App\Models\User\User;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class ViewAgents extends Component
{
    use AuthorizesRequests;

    public bool $show = false;

    public function render()
    {
        $agents = collect();

        if($this->show) {
            $agents = $this->getAgents();
        }

        return view('livewire.view-agents', compact('agents'));
    }

    public function showData()
    {
        $this->show = true;
    }

    private function getAgents()
    {
        if(!auth()->user()->can('viewAgentDetails')) {
          return collect();
        }

        return User::whereHas('roles',
            fn($query) => $query->where('name', 'agent')
        )->with([
            'reservedTickets' => fn($query) => $query->with('event')
                ->whereBetween('reserved_at', [now()->subWeek(), now()])
                ->limit(5)
        ])->get();
    }
}
