<?php

namespace App\Models;

use App\Models\Concerns\HasOrderedActive;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasOrderedActive;

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'summary',
        'description',
        'deliverables',
        'benefits',
        'cta_label',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'deliverables' => 'array',
            'benefits' => 'array',
            'is_active' => 'boolean',
        ];
    }
}
