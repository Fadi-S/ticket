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

        $startDate = $month->startOfMonth();
        $endDate = $month->endOfMonth();

        if($period) {
            $startDate = $period->start;

            $endDate = $period->end;
        }

        $eventCount = 0;

        for ($date = $startDate; $date->lte($endDate); $date->addDay())
        {
            $temps = $templates[$date->dayOfWeek] ?? [];

            foreach ($temps as $template) {
                $start = $date->copy()
                    ->setTime($template->start->hour, $template->start->minute);

                $end = $date->copy()
                    ->setTime($template->end->hour, $template->end->minute);

                if(Event::whereBetween('start', [$start, $end])
                    ->exists())
                    continue;

                $eventCount++;

                Event::create([
                    'start' => $start,

                    'end' => $end,

                    'description' => $template->description,
                    'type_id' => $template->type_id,
                    'overload' => $template->overload,
                    'number_of_places' => $template->number_of_places,
                    'published_at' => $template->start->hours(8),
                ]);
            }

        }

        $this->info("Added $eventCount new events!");

        return 0;
    }
}
