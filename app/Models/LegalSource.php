<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class LegalSource extends Model
{
    protected $fillable = [
        'title', 'slug', 'source_type', 'reference_code', 'provision_reference',
        'page_reference', 'summary', 'source_url', 'document_date',
        'status', 'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'document_date' => 'date',
            'verified_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
