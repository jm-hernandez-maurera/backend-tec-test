<?php

namespace App\Interfaces;

use App\Models\SecurityPrice;

interface SecurityPriceRepositoryInterface
{
    public function createSecurityPrice(array $newSecurityPrice): SecurityPrice;
    public function updateSecurityPrice($securityPriceId, array $newSecurityPrice): void;
}
