<?php

namespace App\Console\Commands;

use App\Models\Login;
use Illuminate\Console\Command;

class ClearLogins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logins:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear session data for records older than 90 days';

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
        $deletedNumber = Login::where('time', '<', now()->subDays(90))
            ->delete();

        $this->info('Cleared ' . $deletedNumber . ' records');

        return 0;
    }
}
