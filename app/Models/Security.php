<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\SecurityType;
use App\Models\SecurityPrice;

class Security extends Model
{
    /** @use HasFactory<\Database\Factories\SecurityFactory> */
    use HasFactory;

    /**
     * Get the security type that owns the security.
     */
    public function securityType(): BelongsTo
    {
        return $this->belongsTo(SecurityType::class);
    }

    /**
     * Get the prices for the security.
     */
    public function securityPrices(): HasMany
    {
        return $this->hasMany(SecurityPrice::class);
    }
}
