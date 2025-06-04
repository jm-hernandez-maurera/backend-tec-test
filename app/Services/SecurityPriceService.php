<?php

namespace App\Services;

use App\Models\SecurityPrice;
use App\Repositories\SecurityPriceRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class SecurityPriceService
{
    /**
     *
     * @var $securityPriceRepository
     */
    protected $securityPriceRepository;

    /**
     * SecurityPriceService constructor.
     *
     * @param SecurityPriceRepository $securityPriceRepository
     *
     */
    public function __construct(SecurityPriceRepository $securityPriceRepository)
    {
        $this->securityPriceRepository = $securityPriceRepository;
    }

    /**
     * Create a SecurityPrice.
     *
     * @param array $newSecurityPrice
     *
     * @return SecurityPrice
     *
     */
    public function createSecurityPrice(array $newSecurityPrice): SecurityPrice
    {
        return $this->securityPriceRepository->createSecurityPrice($newSecurityPrice);
    }

    /**
     * Update a SecurityPrice.
     *
     * @param SecurityPrice $securityPriceId
     * @param array $newSecurityPrice
     *
     * @return void
     *
     */
    public function updateSecurityPrice(SecurityPrice $securityPrice, array $newSecurityPrice): void
    {
        if ($securityPrice->as_of_date->lessThan($newSecurityPrice['as_of_date'])) {
            $this->securityPriceRepository->updateSecurityPrice($securityPrice->id, $newSecurityPrice);
        }
    }
}
