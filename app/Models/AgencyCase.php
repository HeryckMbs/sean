<?php

namespace App\Models;

use App\Models\Concerns\HasOrderedActive;
use Illuminate\Database\Eloquent\Model;

class AgencyCase extends Model
{
    use HasOrderedActive;

    protected $table = 'cases';

    protected $fillable = [
        'title',
        'segment',
        'initial_scenario',
        'challenge',
        'strategy',
        'result',
        'metrics',
        'cta_label',
        'image_path',
        'sort_order',
        'is_featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'metrics' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
