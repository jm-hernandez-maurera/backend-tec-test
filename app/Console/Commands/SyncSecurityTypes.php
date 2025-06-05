<?php

namespace App\Console\Commands;

use App\Jobs\SyncSecurityTypesJob;
use Illuminate\Console\Command;

class SyncSecurityTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-security-types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to dispatch synchronization job for external prices';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        SyncSecurityTypesJob::dispatchSync();
    }
}
