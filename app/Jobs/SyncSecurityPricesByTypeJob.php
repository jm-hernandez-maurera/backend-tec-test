<?php

namespace App\Jobs;

use App\Models\SecurityType;
use App\Services\SyncSecurityPricesService;
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
        if ($this->batch()?->cancelled()) {
            // Determine if the batch has been cancelled...
            Log::notice("Batch SyncSecurityPricesByTypeJob for type {$this->securityType->slug} was cancelled.");
            return;
        }

        $prices = $syncSecurityPricesService->getPricesToSync($this->securityType);

        if (count($prices))
        {
            Log::info("prices");
            Log::info($prices);

            $jobs = [];

            foreach (array_chunk($prices, config('app.external_prices_chunk')) as $chunkPrices){
                $jobs[] = new UpdateSecurityPricesJob($this->securityType, $chunkPrices);
            }

            Bus::chain($jobs)->catch(function ($e) {
                // A job within the chain has failed...
                Log::info("***SyncSecurityPricesByTypeJob: A job within the chain has failed...");
            })->dispatch();

        } else {
            Log::info("There are not New Prices for {$this->securityType->slug}");
        }
    }
}
