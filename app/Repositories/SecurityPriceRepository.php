<?php

namespace App\Repositories;

use App\Interfaces\SecurityPriceRepositoryInterface;
use App\Models\SecurityPrice;
use Illuminate\Support\Facades\Log;

class SecurityPriceRepository implements SecurityPriceRepositoryInterface
{
    public function createSecurityPrice(array $newSecurityPrice): SecurityPrice {
        Log::info("Creating New Security Price");
        $securityPrice = SecurityPrice::create($newSecurityPrice);
        return $securityPrice;
    }

    public function updateSecurityPrice($securityPriceId, array $newSecurityPrice): void {
        Log::info("Updating Security Price {$securityPriceId}");
        SecurityPrice::whereId($securityPriceId)->update($newSecurityPrice);
    }
}
