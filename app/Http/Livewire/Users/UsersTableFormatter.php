<?php

namespace App\Http\Livewire\Users;

use App\Models\Login;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;
use Spatie\Permission\Models\Role;

class UsersTableFormatter extends DataTableComponent
{
    public string $search = '';

    public array $perPageAccepted = [10, 25, 50, 100];

    public string $emptyMessage = 'No users found. Try narrowing your search.';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function rowView() : string
    {
        return 'tables.users';
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function filters(): array
    {
        return [
            'role' => Filter::make(__('Role'))
                ->select(array_merge(['' => '-'], Role::pluck('name', 'name')->toArray())),

            'gender' => Filter::make(__('Gender'))
                ->select([
                    '' => '-',
                    'male' => __('Male'),
                    'female' => __('Female'),
                ]),

            'verified' => Filter::make(__('Phone Verified'))
                ->select([
                    '' => '-',
                    'yes' => 'Yes',
                    'no' => 'No',
                ]),
        ];
    }

    public function columns(): array
    {
        $search = fn(Builder $query, $searchTerm)
        => $query->searchDatabase($searchTerm);

        return [
            Column::make(__('ID'), 'id')
                ->sortable()
                ->searchable($search),
            Column::make(__('Name'), 'name')
                ->sortable()
                ->searchable($search),
            Column::make(__('Arabic Name'), 'arabic_name')
                ->sortable()
                ->addClass('hidden sm:table-cell')
                ->searchable($search),
            Column::make(__('Phone'), 'phone')
                ->sortable()
                ->searchable($search),
            Column::make(__('Last Login'))
                ->sortable(
                    fn(Builder $query, $direction) => $query->orderBy(
                        Login::select('time')
                            ->whereColumn('logins.user_id', 'users.id')
                            ->latest('time')
                            ->take(1),
                        $direction
                    )
                ),
            Column::make(__('Created By')),
            Column::make(__('Role')),
            Column::blank(),
        ];
    }

    public function query(): Builder
    {
        return User::query()
            ->with('creator')

            ->when($this->getFilter('role'),
                fn($query, $role) => $query->role($role)
            )

            ->when($this->getFilter('gender'),
                fn($query, $gender) => $query->where('gender', $gender === 'male' ? 1 : 0)
            )

            ->when($this->getFilter('verified'),
                function ($query, $verified) {
                    $function = $verified === 'yes' ? 'whereNotNull' : 'whereNull';
                    return  $query->$function('verified_at');
                }
            );
    }
}
