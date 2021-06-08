<?php

declare(strict_types=1);

namespace App\Charts;

use App\Models\User\User;
use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class UsersStatusChart extends BaseChart
{
    use AuthorizesRequests;

    public ?array $middlewares = ['auth'];

    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $this->authorize('viewStatistics', User::class);

        $users = \Cache::remember('users.status.active_number', now()->addMinutes(15),
            function () {
                $activeUsers = User::where('activated_at', '<>', null)->count();
                $users = User::where('activated_at', null)->count();

                return [
                    'active' => $activeUsers,
                    'inactive' => $users,
                ];
            });

        return Chartisan::build()
            ->labels([__('Active'), __('Inactive')])
            ->dataset('Users', [$users['active'], $users['inactive']]);
    }
}
