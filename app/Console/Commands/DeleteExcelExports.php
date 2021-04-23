<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteExcelExports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'excel:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete excel files generated';

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
        $files = \Storage::disk('exports')->allFiles();

        $success = \Storage::disk('exports')
            ->delete($files);

        if($success)
            $this->info('Deleted ' . count($files) . ' file(s)');
        else
            $this->error('Error deleting files');

        return 0;
    }
}
