<?php

namespace App\Models;

use App\Models\Concerns\HasOrderedActive;
use Illuminate\Database\Eloquent\Model;

class WorkStep extends Model
{
    use HasOrderedActive;

    protected $fillable = ['step_label', 'title', 'description', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
