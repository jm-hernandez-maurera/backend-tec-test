<?php

namespace App\Repositories;

use App\Interfaces\SecurityRepositoryInterface;
use App\Models\Security;
use Illuminate\Support\Collection;

class SecurityRepository implements SecurityRepositoryInterface
{
    public function getAllSecurities(array $filters = [], array $withs = []): Collection {

        $query = Security::query();

        if(count($withs)) {
            $query->with($withs);
        }

        $model = new Security();
        foreach ($filters as $index => $filter) {
            if (method_exists($model, 'scope' . ucfirst($index))) {
                $query->{$index}($filter);
            }
        }

        return $query->get();
    }
}
