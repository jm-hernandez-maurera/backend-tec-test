<?php

namespace App\Repositories;

use App\Interfaces\SecurityTypeRepositoryInterface;
use App\Models\SecurityType;
use Illuminate\Support\Collection;

class SecurityTypeRepository implements SecurityTypeRepositoryInterface
{
    public function getAllSecurityTypes(): Collection {
        return SecurityType::all();
    }
}
