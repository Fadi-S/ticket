<?php

namespace App\Http\Livewire\Users;

use App\Models\Church;
use App\Models\Login;
use App\Models\Reservation;
use App\Models\User\User;
use App\Notifications\AccountActivated;
use Illuminate\Database\Eloquent\Builder;
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
        'filters' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sorts' => ['except' => ''],
    ];

    public function mount()
    {
        $this->showPerPage = auth()->user()->can('users.view');
    }

    public function rowView(): string
    {
        return 'tables.users';
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function filters(): array
    {
        if (!auth()->user()->can('users.view'))
            return [];

        $churches = \Cache::remember('churches_for_pluck', now()->addDay(), function () {
            return ['' => '-'] + Church::pluck('name', 'id')->toArray();
        });

        $roles = \Cache::remember('roles_for_pluck', now()->addDay(), function () {
            return ['' => '-'] + Role::pluck('name', 'name')->toArray();
        });

        return [
            'role' => Filter::make(__('Role'))
                ->select($roles),

            'church' => Filter::make(__('Church'))
                ->select($churches),

            'verified' => Filter::make(__('Phone Verified'))
                ->select([
                    '' => '-',
                    'yes' => 'Yes',
                    'no' => 'No',
                ]),

            'active' => Filter::make(__('Account Active'))
                ->select([
                    '' => '-',
                    'yes' => 'Yes',
                    'no' => 'No',
                ]),

            'gender' => Filter::make(__('Gender'))
                ->select([
                    '' => '-',
                    'male' => __('Male'),
                    'female' => __('Female'),
                ]),

            'disabled' => Filter::make(__('Disabled'))
                ->select([
                    '' => '-',
                    'yes' => __('Yes'),
                    'no' => __('No'),
                ]),
        ];
    }

    public function columns(): array
    {
        $search = fn(Builder $query, $searchTerm) => $query->searchDatabase($searchTerm);

        return [
            Column::make(__('ID'), 'id')
                ->sortable()
                ->searchable($search),
            Column::make(__('Name'), config('settings.arabic_name_only') ? 'arabic_name' : 'name')
                ->sortable()
                ->searchable($search),
            Column::make(__('Arabic Name'), 'arabic_name')
                ->sortable()
                ->addClass('hidden sm:table-cell')
                ->searchable($search)
                ->hideIf(config('settings.arabic_name_only')),
            Column::make(__('Phone'), 'phone')
                ->sortable()
                ->searchable($search),
            Column::make(__('National ID'), 'national_id')
                ->sortable()
                ->searchable($search),
            Column::make(__('Gender'), 'gender')
                ->sortable(),
            Column::make(__('Allowed'), 'disabled_at')
                ->hideIf(auth()->user()->cannot('disable', User::class))
                ->sortable(),
            Column::make(__('Location of stay'), 'location_id')
                ->sortable(),
            Column::make(__('Church'), 'church_id')
                ->sortable()
                ->hideIf(!auth()->user()->can("activateUser")),
            Column::make(__('Last Login'))
                ->sortable(
                    fn(Builder $query, $direction) => $query->orderBy(
                        Login::select('time')
                            ->whereColumn('logins.user_id', 'users.id')
                            ->latest('time')
                            ->take(1),
                        $direction
                    )
                )->hideIf(!auth()->user()->can("users.view")),
            Column::make(__('Last Reservation'))
                ->sortable(
                    fn(Builder $query, $direction) => $query->orderBy(
                        Reservation::select('created_at')
                            ->whereColumn('reservations.user_id', 'users.id')
                            ->latest('created_at')
                            ->take(1),
                        $direction
                    )
                )->hideIf(!auth()->user()->can("users.view")),
            Column::make(__('Created By'))
                ->hideIf(!auth()->user()->can("users.view")),
            Column::make(__('Role'))
                ->hideIf(!auth()->user()->can("users.view")),
            Column::blank(),
        ];
    }

    public function activate(User $user)
    {
        if(! auth()->user()->can('activateUser')) {
            return;
        }

        $user->activated_at = ($user->isActive() ? null : now());
        $user->save();

        $user->notify(new AccountActivated);

        $this->dispatchBrowserEvent('message', [
            'level' => 'success',
            'message' => __(':name Activated Successfully', ['name' => $user->locale_name]),
            'important' => false,
        ]);
    }

    public function toggleDisabled(User $user)
    {
        if(auth()->user()->cannot('activateUser') || $user->isSignedIn() || $user->isAdmin()) {
            return;
        }

        $user->disabled_at = ($user->isDisabled() ? null : now());
        $user->save();

        $this->dispatchBrowserEvent('message', [
            'level' => 'success',
            'message' => __(':name Disabled Successfully', ['name' => $user->locale_name]),
            'important' => false,
        ]);
    }

    public function addToChurch(User $user)
    {
        if(! auth()->user()->can('activateUser')) {
            return;
        }

        $user->church_id = (!! $user->church_id) ? null : auth()->user()->church_id;
        $user->save();
    }

    public function query(): Builder
    {
        $query = User::query();
        if (!auth()->user()->can("users.view"))
            $query = User::where('creator_id', '=', \Auth::id())
                ->orWhere('id', '=', \Auth::id());

        return $query
            ->with(
                'creator:id,username,name,arabic_name',
                'location:id,name',
                'church:id,name',
                'roles:id,name',
            )
            ->addSelect([
                'last_reservation_at' => Reservation::select('created_at')
                    ->whereColumn('user_id', 'users.id')
                    ->latest()
                    ->take(1),
//                'reserved_by' => User::select('arabic_name')
//                    ->join('reservations', 'reservations.user_id', 'users.id')
//                    ->join('tickets', 'tickets.reserved_by', '=', 'users.id')
//                    ->whereColumn('reservations.ticket_id', 'tickets.id')
//                    ->whereColumn('reservations.user_id', 'users.id')
//                    ->latest('tickets.created_at')
//                    ->take(1),
                'last_login_at' => Login::select('time')
                    ->whereColumn('user_id', 'users.id')
                    ->latest('time')
                    ->take(1),
            ])
            ->withCasts([
                'last_reservation_at' => 'datetime',
                'last_login_at' => 'datetime',
            ])
            ->when($this->getFilter('role'),
                fn($query, $role) => $query->role($role)
            )

            ->when($this->getFilter('church'),
                fn($query, $church) => $query->where('church_id', $church)
            )

            ->when($this->getFilter('gender'),
                fn($query, $gender) => $query->where('gender', $gender === 'male' ? 1 : 0)
            )

            ->when($this->getFilter('verified'),
                function ($query, $verified) {
                    $function = $verified === 'yes' ? 'whereNotNull' : 'whereNull';
                    return $query->$function('verified_at');
                }
            )

            ->when($this->getFilter('disabled'),
                function ($query, $disabled) {
                    $function = $disabled === 'yes' ? 'whereNotNull' : 'whereNull';
                    return $query->$function('disabled_at');
                }
            )

            ->when($this->getFilter('active'),
                function ($query, $activated) {
                    $function = $activated === 'yes' ? 'whereNotNull' : 'whereNull';
                    return $query->$function('activated_at');
                }
            );
    }
}
