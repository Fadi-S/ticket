<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\IncomingExceptionEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if(!isset($_COOKIE['dark']) || $_COOKIE['dark'] === 'true') {
            Telescope::night();
        }

        $this->hideSensitiveRequestDetails();

        Telescope::filter(function (IncomingEntry $entry) {
            if ($this->app->environment('local')) {
                return true;
            }

            return $entry->isReportableException() ||
                   $entry->isFailedRequest() ||
                   $entry->isFailedJob() ||
                   $entry->isScheduledTask() ||
                   $entry->hasMonitoredTag();
        });

//        Telescope::afterStoring(function (array $entries, $batchId) {
//            foreach ($entries as $entry) {
//                if ($entry instanceof IncomingExceptionEntry) {
//                    logger()->channel('slack')->critical(
//                        $entry->exception,
//                        [
//                            'environment' => app()->environment(),
//                            'url' => app()->runningInConsole() ? 'CLI' : request()->method() . ' ' . request()->fullUrl(),
//                            'user' => $entry->content['user'] ?? '-',
//                            'view in Telescope' => url('telescope/exceptions/' . $entry->uuid),
//                        ]
//                    );
//                }
//            }
//        });
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     *
     * @return void
     */
    protected function hideSensitiveRequestDetails()
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewTelescope', function ($user) {
            return in_array($user->email, [
                'admin@alsharobim.com'
            ]);
        });
    }
}
