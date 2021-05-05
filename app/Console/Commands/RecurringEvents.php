<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Period;
use App\Models\Template;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RecurringEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:create  {--month=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create recurring events';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $templates = Template::active()->get()->groupBy('day_of_week');

        \Cache::set('latest_automatic_events', time());

        $month = now()->month(now()->month + 1);
        $period = Period::current();
        if($this->hasOption('month')) {
            $month = now()->month($this->option('month'));
        }

        $days = $month->daysInMonth;
        $day = 1;

        if($period) {
            $day = $period->day;

            $days = $period->start->diffInDays($period->end);

            $month = $period->start;
        }

        $eventCount = 0;

        for (;$day<=$days; $day++)
        {
            $date = $month->copy()->days($day);

            $temps = $templates[$date->dayOfWeek] ?? [];

            foreach ($temps as $template) {
                $start = $date->copy()
                    ->setTime($template->start->hour, $template->start->minute);

                if(Event::where('start', $start)->exists())
                    continue;

                $eventCount++;

                Event::create([
                    'start' => $start,

                    'end' => $date->copy()
                        ->setTime($template->end->hour, $template->end->minute),

                    'description' => $template->description,
                    'type_id' => $template->type_id,
                    'overload' => $template->overload,
                    'number_of_places' => $template->number_of_places,
                    'published_at' => now(),
                ]);
            }

        }

        $this->info("Added $eventCount new events!");

        return 0;
    }
}
