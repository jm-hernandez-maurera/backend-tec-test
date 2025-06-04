<?php

namespace App\Services;

use App\MockService\PricesServices;
use App\Models\Security;
use App\Models\SecurityType;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class SyncSecurityPricesService
{
    /**
     *
     * @var $securityPriceService
     */
    protected $securityPriceService;

    /**
     *
     * @var $securityService
     */
    protected $securityService;

    /**
     * Create a new class instance.
     */
    public function __construct(SecurityService $securityService, SecurityPriceService $securityPriceService)
    {
        $this->securityPriceService = $securityPriceService;
        $this->securityService = $securityService;
    }

    /**
     * Use the External Service to get the prices to sync by a security type.
     *
     * @param SecurityType $securityType
     *
     * @return array
     *
     */
    public function getPricesToSync(SecurityType $securityType): array
    {
        Log::info("getPricesToSync");
        $response = PricesServices::get($securityType->slug);
        Log::info($response);
        if($result = $response->getContent()) {
            $prices = json_decode($result, true);
            if(isset($prices['results'])){
                return $prices['results'];
            }
        }
        return [];
    }

    /**
     * Get Securities Collection from a given type a symbol.
     *
     * @param SecurityType $securityType
     * @param array $prices
     *
     * @return Collection
     *
     */
    public function getSecuritiesToSync(SecurityType $securityType, array $prices): Collection
    {
        $names = Arr::pluck($prices, 'symbol');
        Log::info($names);

        $securities = $this->securityService->getAll([
            'ofType' => $securityType->id,
            'ofSymbols' => $names
        ], ['securityPrices']);

        Log::info($securities);
        return $securities;
    }

    /**
     * Update a SecurityPrice by a given Security.
     *
     * @param Security $security
     * @param array $price
     *
     * @return void
     *
     */
    private function updateSecurityPriceBySecurity(Security $security, array $price): void
    {
        if ($security->securityPrices->isEmpty()) {
            $this->securityPriceService->createSecurityPrice([
                'security_id' => $security->id,
                'last_price' => $price['price'],
                'as_of_date' => $price['last_price_datetime'],
            ]);
        } else {
            $securityPrice = $security->securityPrices->last();
            $this->securityPriceService->updateSecurityPrice($securityPrice, [
                'last_price' => $price['price'],
                'as_of_date' => Carbon::make($price['last_price_datetime'])->timezone('UTC'),
            ]);
        }
    }

    /**
     * Sync Security PricesByType.
     *
     * @param array $prices
     * @param SecurityType $securityType
     * @return void
     *
     */
    public function syncSecurityPricesByType(SecurityType $securityType, array $prices): void
    {
        $securities = $this->getSecuritiesToSync($securityType, $prices);

        if($securities->isNotEmpty()) {

            foreach ($prices as $price) {
                Log::info($price);
                $security = $securities->where('symbol', $price['symbol'])->first();  //Asumo que el symbol es unico ?
                Log::info("SECURITY");
                Log::info($security);
                $this->updateSecurityPriceBySecurity($security, $price);
            }
        } else {
            Log::info("There are not Securities that belongs to security type: {$securityType->slug}");
        }
    }
}

