<?php

namespace App\Jobs;

use App\Models\SecurityType;
use App\Services\SyncSecurityPricesService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateSecurityPricesJob implements ShouldQueue
{
    use Queueable;

    /**
     *
     * @var $prices
     */
    protected $prices;

    /**
     *
     * @var $securityType
     */
    protected $securityType;

    /**
     * Create a new job instance.
     */
    public function __construct(SecurityType $securityType, array $prices)
    {
        $this->prices = $prices;
        $this->securityType = $securityType;
    }

    /**
     * Execute the job.
     */
    public function handle(SyncSecurityPricesService $syncSecurityPricesService): void
    {
        $syncSecurityPricesService->syncSecurityPricesByType($this->securityType, $this->prices);
    }
}
