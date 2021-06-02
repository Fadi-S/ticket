<?php

namespace App\Console\Commands;

use App\Models\User\User;
use Illuminate\Console\Command;

class DeleteGuests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guests:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete guest users after their expiration date';

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
        User::where('expiration', '<=', now())
            ->where('expiration', '<>', null)
            ->delete();

        $this->info('Deleted guests until ' . now()->format('Y-m-d'));

        return 0;
    }
}
