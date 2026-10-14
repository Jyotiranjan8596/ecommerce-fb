<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    public const AUDIENCES = [
        'pos' => 'POS',
        'prime' => 'Prime Customers',
        'non_prime' => 'Non-prime Customers',
    ];

    protected $fillable = [
        'title',
        'description',
        'audiences',
        'redemption_instructions',
        'terms',
        'cta_label',
        'cta_url',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected $casts = [
        'audiences' => 'array',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->where(function (Builder $query) {
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function (Builder $query) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }

    public function isAvailableTo(string $audience): bool
    {
        return in_array($audience, $this->audiences ?? [], true);
    }
}
