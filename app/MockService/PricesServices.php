<?php

namespace App\MockService;

use Illuminate\Http\Response;

class PricesServices
{
    static function getBySecurityTypeSlug($security_type_slug){

        $mutual_funds = [
            [
                'symbol' => 'APPL',
                'price' => 190.97,
                'last_price_datetime' => '2023-11-02T17:31:18-04:00'
            ], [
                'symbol' => 'TSLA',
                'price' => 244.42,
                'last_price_datetime' => '2023-11-01T17:32:11-04:00'
            ], [
                'symbol' => 'VSMPX',
                'price' => 100.42,
                'last_price_datetime' => '2023-10-30T17:32:11-04:00'
            ], [
                'symbol' => 'FXAIX',
                'price' => 200.42,
                'last_price_datetime' => '2022-10-30T17:32:11-04:00'
            ]

        ];

        switch ($security_type_slug) {
            case "mutual_funds":
                $results = $mutual_funds;
                break;
            default:
                $results = [];
        }

        return response()->json([
            "results" => $results
        ], Response::HTTP_OK);
    }
}
