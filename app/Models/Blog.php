<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blog extends Model
{
    protected $table = 'blog';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'author_id',
        'category',
        'tags',
        'views',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'views' => 'integer',
        'published_at' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Parse PostgreSQL text[] or JSON string into array of tags.
     */
    public function getTagsArrayAttribute(): array
    {
        $raw = $this->attributes['tags'] ?? null;
        if (empty($raw)) {
            return [];
        }
        if (is_array($raw)) {
            return $raw;
        }
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            return $decoded;
        }
        $trimmed = trim($raw, '{}');
        if ($trimmed === '') {
            return [];
        }
        return array_values(array_filter(array_map(function ($item) {
            return trim($item, " \t\n\r\0\x0B\"'");
        }, explode(',', $trimmed))));
    }

    public function getTagsStringAttribute(): string
    {
        return implode(', ', $this->tags_array);
    }
}
