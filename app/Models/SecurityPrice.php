<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityPrice extends Model
{
    /** @use HasFactory<\Database\Factories\SecurityPriceFactory> */
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = ['id'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'as_of_date' => 'datetime:Y-m-d H:i:s',
        ];
    }

    /**
     * Get the security that owns the security price.
     */
    public function security(): BelongsTo
    {
        return $this->belongsTo(Security::class);
    }
}
