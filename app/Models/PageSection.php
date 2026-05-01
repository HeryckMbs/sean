<?php

namespace App\Models;

use App\Models\Concerns\HasOrderedActive;
use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    use HasOrderedActive;

    protected $fillable = [
        'key',
        'eyebrow',
        'title',
        'subtitle',
        'body',
        'cta_label',
        'cta_url',
        'media_path',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
