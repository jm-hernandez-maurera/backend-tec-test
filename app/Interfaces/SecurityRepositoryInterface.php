<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface SecurityRepositoryInterface
{
    public function getAllSecurities(array $filters = [], array $withs = []): Collection;
}
