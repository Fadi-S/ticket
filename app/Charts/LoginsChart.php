<?php

declare(strict_types = 1);

namespace App\Charts;

use App\Models\Login;
use App\Models\User\User;
use Carbon\Carbon;
use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class LoginsChart extends BaseChart
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
        $this->authorize('viewStatistics');

        $days = collect([]);
        for ($date = now()->startOfDay()->subDays(7); $date->lte(now()); $date->addDay()) {
            $days->push($date->copy());
        }

        $logins = Login::whereBetween('time', [$days->first()->startOfDay(), $days->last()->endOfDay()])->pluck('time');

        $loginsPerHour = collect();

        $hours = collect();
        foreach ($days as $day) {
            for ($i = 0; $i <=23; $i ++) {
                $hour = $day->copy()->hours($i)->startOfHour();

                $hours->push($hour->translatedFormat('l ha'));

                $loginsPerHour->push($logins->filter(fn($time) => $time->between($hour, $hour->copy()->endOfHour()))->count());
            }
        }

        return Chartisan::build()
            ->labels($hours->toArray())
            ->dataset('Logins', $loginsPerHour->toArray());
    }
}
