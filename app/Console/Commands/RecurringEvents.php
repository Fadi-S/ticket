<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RecurringEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:create';

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
        $eventIds = \Cache::get('recurring');

        $this->info(json_encode($eventIds));

        return 0;
    }
}
