<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = [
        'key', 'group', 'label', 'type',
        'value_es', 'value_en', 'value_pt', 'sort_order',
    ];

    public function valueFor(string $lang): ?string
    {
        $col = 'value_' . $lang;
        return $this->{$col} ?? $this->value_es;
    }

    public static function bag(): array
    {
        return Cache::remember('site_settings_bag', 60, function () {
            $rows = static::all();
            $bag = [];
            foreach ($rows as $row) {
                $bag[$row->key] = [
                    'es' => $row->value_es,
                    'en' => $row->value_en,
                    'pt' => $row->value_pt,
                    'type' => $row->type,
                ];
            }
            return $bag;
        });
    }

    public static function forgetCache(): void
    {
        Cache::forget('site_settings_bag');
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::forgetCache());
        static::deleted(fn () => static::forgetCache());
    }
}
