<?php

namespace App\Jobs;

use App\Models\Security;
use App\Models\SecurityType;
use App\Services\SecurityPriceService;
use App\Services\SecurityService;
use App\Services\SecurityTypeService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\MockService\PricesServices;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

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
    public function handle(
        SecurityPriceService $securityPriceService,
        SecurityService $securityService,
        SecurityTypeService $securityTypeService): void
    {

        $securityTypes = $securityTypeService->getAll();

        foreach ($securityTypes as $securityType) {
            $prices = json_decode((PricesServices::get($securityType->slug))->getContent(), true)['results'];

            if (count($prices)) {
                Log::info($prices);
                $names = Arr::pluck($prices, 'symbol');
                Log::info($names);

                $securities = $securityService->getAll([
                    'ofType' => $securityType->id,
                    'ofSymbols' => $names
                ], ['securityPrices']);
                Log::info($securities);

                if($securities->isNotEmpty()) {
                    foreach ($prices as $price) {
                        Log::info($price);
                        $security = $securities->where('symbol', $price['symbol'])->first();  //Asumo que el symbol es unico ?
                        Log::info("SECURITY");
                        Log::info($security);
                        if ($security->securityPrices->isEmpty()) {
                            $securityPriceService->createSecurityPrice([
                                'security_id' => $security->id,
                                'last_price' => $price['price'],
                                'as_of_date' => $price['last_price_datetime'],
                            ]);
                        } else {
                            $securityPrice = $security->securityPrices->last();
                            $securityPriceService->updateSecurityPrice($securityPrice, [
                                'last_price' => $price['price'],
                                'as_of_date' => Carbon::make($price['last_price_datetime'])->timezone('UTC'),
                            ]);
                        }
                    }
                } else {
                    Log::info("No hay Securities para {$price['symbol']}");
                }

            } else {
                Log::info("No hay Prices Nuevos para {$securityType->slug}");
            }

        }

    }
}
