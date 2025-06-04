<?php

namespace App\MockService;

use Illuminate\Http\Response;

class PricesServices
{
    static function get($security_type_slug = null){

        $mutual_funds = [
            [
                'symbol' => 'APPL',
//                'price' => 188.97,
                'price' => 189.97,
                //'last_price_datetime' => '2023-10-30T17:31:18-04:00'
                'last_price_datetime' => '2023-10-30T17:31:18-04:00'
            ], [
                'symbol' => 'TSLA',
                'price' => 244.42,
                'last_price_datetime' => '2023-10-31T17:32:11-04:00'
            ]
        ];

//        $individual_funds = [
//            [
//                'symbol' => 'APPL2',
//                'price' => 200.97,
//                'last_price_datetime' => '2023-10-30T17:31:18-04:00'
//            ], [
//                'symbol' => 'TSLA2',
//                'price' => 100.42,
//                'last_price_datetime' => '2023-10-30T17:32:11-04:00'
//            ]
//        ];

        switch ($security_type_slug) {
            case "mutual_funds":
                $results = $mutual_funds;
                break;
//            case "individual_funds":
//                $results = $individual_funds;
//                break;
            default:
                $results = [];
        }

        return response()->json([
            "results" => $results
        ], Response::HTTP_OK);
    }
}
