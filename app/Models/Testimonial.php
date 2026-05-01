<?php

namespace App\Models;

use App\Models\Concerns\HasOrderedActive;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasOrderedActive;

    protected $fillable = [
        'author_name',
        'company',
        'role',
        'content',
        'avatar_path',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
