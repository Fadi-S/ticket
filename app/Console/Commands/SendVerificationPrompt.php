<?php

namespace App\Console\Commands;

use App\Mail\VerificationPrompt;
use App\Models\User\User;
use App\Notifications\VerificationNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendVerificationPrompt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verification:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email to users to verify their accounts';

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
        $number = \Cache::get('sent-users', 0);
        $step = 40;

        $users = User::where('verified_at', null)
            ->skip($step * $number)
            ->take($step)
            ->get(['email', 'name', 'arabic_name', 'locale']);


        $user = User::find(2);
        $user->notifyNow(new VerificationNotification());

        foreach ($users as $user) {
            if($user->email == null)
                continue;

            app()->setLocale($user->locale);

            $user->notifyNow(new VerificationNotification());
        }

        $this->info("Emails sent to {$users->count()} user");

        \Cache::put('sent-users', $number + 1);

        return 0;
    }
}
