<?php

namespace App\Models;

use App\Models\Concerns\HasOrderedActive;
use Illuminate\Database\Eloquent\Model;

class TargetAudience extends Model
{
    use HasOrderedActive;

    protected $fillable = ['title', 'description', 'icon', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
