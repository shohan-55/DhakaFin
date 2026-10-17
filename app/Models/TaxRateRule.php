<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class TaxRateRule extends Model
{
    protected $fillable = [
        'code', 'tax_type', 'category', 'description', 'rate_basis_points',
        'effective_from', 'effective_until', 'reference_code',
        'provision_reference', 'source_url', 'status', 'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'rate_basis_points' => 'integer',
            'effective_from' => 'date',
            'effective_until' => 'date',
            'verified_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
