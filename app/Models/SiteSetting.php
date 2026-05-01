<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'group',
        'label',
        'value',
        'type',
        'sort_order',
    ];

    public function typedValue(): mixed
    {
        return match ($this->type) {
            'json' => json_decode($this->value ?: '[]', true) ?: [],
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            default => $this->value,
        };
    }

    public static function values(): array
    {
        return static::query()
            ->get()
            ->mapWithKeys(fn (SiteSetting $setting) => [$setting->key => $setting->typedValue()])
            ->all();
    }

    public static function setValue(string $key, mixed $value): void
    {
        $setting = static::query()->where('key', $key)->firstOrFail();

        $setting->value = $setting->type === 'json'
            ? json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            : (string) $value;

        $setting->save();
    }
}
