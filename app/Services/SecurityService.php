<?php

namespace App\Services;

use App\Repositories\SecurityRepository;
use Illuminate\Support\Collection;

class SecurityService
{
    /**
     *
     * @var $securityRepository
     */
    protected $securityRepository;

    /**
     * SecurityService constructor.
     *
     * @param SecurityRepository $securityRepository
     *
     */
    public function __construct(SecurityRepository $securityRepository)
    {
        $this->securityRepository = $securityRepository;
    }

    /**
     * Get all securities.
     *
     * @return Collection
     */
    public function getAll(array $filters = [], array $withs = []): Collection
    {
        return $this->securityRepository->getAllSecurities($filters, $withs);
    }

}
