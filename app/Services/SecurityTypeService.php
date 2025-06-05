<?php

namespace App\Services;

use App\Repositories\SecurityTypeRepository;
use Illuminate\Support\Collection;

class SecurityTypeService
{
    /**
     *
     * @var $securityTypeRepository
     */
    protected $securityTypeRepository;

    /**
     * SecurityTypeService constructor.
     *
     * @param SecurityTypeRepository $securityTypeRepository
     *
     */
    public function __construct(SecurityTypeRepository $securityTypeRepository)
    {
        $this->securityTypeRepository = $securityTypeRepository;
    }

    /**
     * Get all securityTypes.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->securityTypeRepository->getAllSecurityTypes();
    }
}
