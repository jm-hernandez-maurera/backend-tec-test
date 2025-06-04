<?php

namespace App\Jobs;

use App\Services\SecurityTypeService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;

class SyncSecurityTypesJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(SecurityTypeService $securityTypeService): void
    {
        $securityTypes = $securityTypeService->getAll();

        $jobBatchs = [];
        foreach ($securityTypes as $securityType) {
            $jobBatchs[] = new SyncSecurityPricesByTypeJob($securityType);
        }

        $batch = Bus::batch($jobBatchs)->before(function (Batch $batch) {
            // The batch has been created but no jobs have been added...
            Log::info("***SyncSecurityTypesJob: The batch has been created but no jobs have been added..");
        })->progress(function (Batch $batch) {
            // A single job has completed successfully...
            Log::info("***SyncSecurityTypesJob: A single job has completed successfully...");
        })->then(function (Batch $batch) {
            // All jobs completed successfully...
            Log::info("***SyncSecurityTypesJob: All jobs completed successfully...");
        })->catch(function (Batch $batch, Throwable $e) {
            // First batch job failure detected...
            Log::info("***SyncSecurityTypesJob: First batch job failure detected...");
        })->finally(function (Batch $batch) {
            // The batch has finished executing...
            Log::info("***SyncSecurityTypesJob: The batch has finished executing...");
        })->dispatch();

    }
}
