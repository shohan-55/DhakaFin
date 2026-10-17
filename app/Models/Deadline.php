<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Deadline extends Model
{
    protected $fillable = ['title', 'slug', 'description', 'due_on', 'category', 'source_url', 'status', 'published_at'];

    protected function casts(): array
    {
        return [
            'due_on' => 'date',
            'published_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
