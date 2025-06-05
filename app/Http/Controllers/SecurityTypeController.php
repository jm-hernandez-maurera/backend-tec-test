<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSecurityTypeRequest;
use App\Http\Requests\UpdateSecurityTypeRequest;
use App\Jobs\SyncSecurityPricesByTypeJob;
use App\Models\SecurityType;
use App\Services\SyncSecurityPricesService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SecurityTypeController extends Controller
{
    protected $syncSecurityPricesService;

    public function __construct(SyncSecurityPricesService $syncSecurityPricesService)
    {
        $this->syncSecurityPricesService = $syncSecurityPricesService;
    }

    /**
     * Synchronize and update the prices for a securityType in storage.
     */
    public function syncAndUpdatePrices(SecurityType $securityType)
    {
        SyncSecurityPricesByTypeJob::dispatch($securityType);
        return response()->json(['Security Prices will be synchronized.'], Response::HTTP_OK);
    }
}
