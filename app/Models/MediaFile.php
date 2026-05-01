<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaFile extends Model
{
    protected $fillable = [
        'original_name',
        'path',
        'disk',
        'mime_type',
        'size',
        'collection',
        'alt_text',
    ];
}
