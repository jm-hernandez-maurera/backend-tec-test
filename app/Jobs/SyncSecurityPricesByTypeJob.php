<?php

namespace App\Jobs;

use App\Models\SecurityType;
use App\Services\SyncSecurityPricesService;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Batchable;

class SyncSecurityPricesByTypeJob implements ShouldQueue
{
    use Queueable, Batchable;

    /**
     *
     * @var $securityType
     */
    protected $securityType;

    /**
     * Create a new job instance.
     */
    public function __construct(SecurityType $securityType)
    {
        $this->securityType = $securityType;
    }

    /**
     * Execute the job.
     */
    public function handle(SyncSecurityPricesService $syncSecurityPricesService): void
    {
        if ($this->batch()->cancelled()) {
            // Determine if the batch has been cancelled...
            Log::notice("Batch SyncSecurityPricesByTypeJob for type {$this->securityType->slug} was cancelled.");
            return;
        }

        $prices = $syncSecurityPricesService->getPricesToSync($this->securityType);

        if (count($prices))
        {
            Log::info($prices);

            $jobs = [];

            foreach (array_chunk($prices, config('app.external_prices_chunk')) as $chunkPrices){
                $jobs[] = new UpdateSecurityPricesJob($this->securityType, $chunkPrices);
            }

            $batch = Bus::batch([$jobs])->before(function (Batch $batch) {
                // The batch has been created but no jobs have been added...
                Log::info("***SyncSecurityPricesByTypeJob: The batch has been created but no jobs have been added..");
            })->progress(function (Batch $batch) {
                // A single job has completed successfully...
                Log::info("***SyncSecurityPricesByTypeJob: A single job has completed successfully...");
            })->then(function (Batch $batch) {
                // All jobs completed successfully...
                Log::info("***SyncSecurityPricesByTypeJob: All jobs completed successfully...");
            })->catch(function (Batch $batch, Throwable $e) {
                // First batch job failure detected...
                Log::info("***SyncSecurityPricesByTypeJob: First batch job failure detected...");
            })->finally(function (Batch $batch) {
                // The batch has finished executing...
                Log::info("***SyncSecurityPricesByTypeJob: The batch has finished executing...");
            })->dispatch();

        } else {
            Log::info("There are not New Prices for {$this->securityType->slug}");
        }
    }
}
