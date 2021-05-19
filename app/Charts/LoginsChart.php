<?php

declare(strict_types = 1);

namespace App\Charts;

use App\Models\Login;
use App\Models\User\User;
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
        for ($date = now()->subDays(7); $date->lte(now()); $date->addDay()) {
            $days->push($date->copy());
        }

        $logins = collect(Login::whereBetween('time', [$days->first(), $days->last()])->pluck('time'))
            ->groupBy(fn($time) => $time->format('Y-m-d'));

        return Chartisan::build()
            ->labels($days->map(fn($day) => $day->translatedFormat('l'))->toArray())
            ->dataset('Logins', $logins->map(fn($day) => $day->count())->values()->toArray());
    }
}
