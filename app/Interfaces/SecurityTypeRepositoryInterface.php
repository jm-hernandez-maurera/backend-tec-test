<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface SecurityTypeRepositoryInterface
{
    public function getAllSecurityTypes(): Collection;
}
