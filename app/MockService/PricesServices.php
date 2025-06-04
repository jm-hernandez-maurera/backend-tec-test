<?php

namespace App\MockService;

class PricesServices
{
    static function get(){
        
        return "{
            'results': [
            {
                'symbol': 'APPL',
                'price': 188.97,
                'last_price_datetime': '2023-10-30T17:31:18-04:00'
            }, {
                'symbol': 'TSLA',
                'price': 244.42,
                'last_price_datetime': '2023-10-30T17:32:11-04:00'
            }
            ]
        }";
    }
}
