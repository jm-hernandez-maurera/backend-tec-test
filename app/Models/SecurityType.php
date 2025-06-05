<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SecurityType extends Model
{
    /** @use HasFactory<\Database\Factories\SecurityTypeFactory> */
    use HasFactory;

    /**
     * Get the securities for the security type.
     */
    public function securities(): HasMany
    {
        return $this->hasMany(Security::class);
    }
}
