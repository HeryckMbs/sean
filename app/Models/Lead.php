<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name',
        'company',
        'email',
        'phone',
        'whatsapp',
        'message',
        'service_interests',
        'source',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'integration_status',
        'webhook_endpoint',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'service_interests' => 'array',
        ];
    }
}
