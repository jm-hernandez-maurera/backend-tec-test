<?php

namespace App\Jobs;

use App\Models\Security;
use App\Models\SecurityType;
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
    public function handle(): void
    {
        $securityTypes = SecurityType::all();

        foreach ($securityTypes as $securityType) {
            $prices = json_decode((PricesServices::get($securityType->slug))->getContent(), true)['results'];

            if (count($prices)) {
                Log::info($prices);
                $names = Arr::pluck($prices, 'symbol');
                Log::info($names);
                $securities = Security::with(['securityPrices'])
                    ->where('security_type_id', $securityType->id)
                    ->whereIn('symbol', $names)
                    ->get();
                Log::info($securities);

                if($securities->isNotEmpty()) {
                    foreach ($prices as $price) {
                        Log::info($price);
                        $security = $securities->where('symbol', $price['symbol'])->first();  //Asumo que el symbol es unico ?
                        Log::info("SECURITY");
                        Log::info($security);
                        if ($security->securityPrices->isEmpty()) {
                            //crear
                            Log::info("Crear");
                            $securityPrice = $security->securityPrices()->create([
                                'last_price' => $price['price'],
                                'as_of_date' => $price['last_price_datetime'],
                            ]);
                        } else {
                            //update
                            Log::info("Update");
                            $securityPrice = $security->securityPrices->last();
                            Log::info($securityPrice);
                            //validar que no este actualizado ya
                            $priceDatetime = Carbon::make($price['last_price_datetime'])->timezone('UTC');
                            Log::info($priceDatetime);
                            Log::info($securityPrice->as_of_date);
                            if ($securityPrice->as_of_date != $price['last_price_datetime']) {
                                //actualizar si no ya esta actualizado
                                $securityPrice->last_price = $price['price'];
                                $securityPrice->as_of_date = $price['last_price_datetime'];
                                $securityPrice->save();
                            }
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
