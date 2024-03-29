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
    protected $signature = 'events:create  {--month=} {--day=} {--start=} {--end=} {--publish=} {--type=}';

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
        $templates = Template::active()
            ->when($this->hasOption('type'), fn($query) => $query->type($this->option('type')))
            ->get()
            ->groupBy('day_of_week');

        \Cache::set('latest_automatic_events', time());

        $month = now()->month(now()->month + 1);
        $period = Period::current($this->option('type'));

        if($this->hasOption('month')) {
            $month = now()->month($this->option('month'));
        }

        $day = now();

        $startDate = $month->copy()->startOfMonth();
        $endDate = $month->copy()->endOfMonth();

        if($period) {
            $startDate = $period->start->copy();

            $endDate = $period->end->copy();

            $day = $period->start->copy();
        }

        if($this->hasOption('day')) {
            $day = now()->next((int) $this->option('day'));
        }

        if($this->hasOption('start')) {
            if(!$this->hasOption('end')) {
                $this->error('You must specify --end option!');

                return 0;
            }

            $startDate = Carbon::parse($this->option('start'));

            $endDate = Carbon::parse($this->option('end'));

            if(!$this->hasOption('publish')) {
                $this->warn('You have not specified a publish date, using default: ' . $day->format('Y-m-d h:i a'));
            }else {
                $day = Carbon::createFromFormat('Y-m-d-h-i-A', $this->option('publish'));
            }
        }

        $eventCount = 0;

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay())
        {
            $temps = $templates[$date->dayOfWeek] ?? [];

            foreach ($temps as $template) {
                $start = $date->copy()
                    ->setTime($template->start->hour, $template->start->minute);

                $end = $date->copy()
                    ->setTime($template->end->hour, $template->end->minute);

                if($end->lessThan($start))
                    $end->addDay();

                if(Event::whereBetween('start', [$start, $end])->where('description', '=', $template->description)
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
                    'deacons_number' => $template->deacons_number,
                    'published_at' => $day,
                    'church_id' => $template->church_id,
                ]);
            }

        }

        $this->info("Added $eventCount new events!");

        return 0;
    }
}
