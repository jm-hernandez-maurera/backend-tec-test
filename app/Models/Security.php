<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\SecurityType;
use App\Models\SecurityPrice;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Security extends Model
{
    /** @use HasFactory<\Database\Factories\SecurityFactory> */
    use HasFactory;

    /**
     * Scope a query to only include securities of a given type.
     */
    public function scopeOfType($query, $security_type_id)
    {
        return $query->where('security_type_id', $security_type_id);
    }

    /**
     * Scope a query to only include securities of a given symbol's names.
     */
    public function scopeOfSymbols(Builder $query, array $symbols)
    {
        return $query->whereIn('symbol', $symbols);
    }

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
    public function securityPrices()
    {
        return $this->hasMany(SecurityPrice::class);
    }
}
