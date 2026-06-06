<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuCategory extends Model
{
    protected $fillable = [
        'slug', 'icon',
        'name_es', 'name_en', 'name_pt',
        'description_es', 'description_en', 'description_pt',
        'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort_order');
    }

    public function activeItems(): HasMany
    {
        return $this->items()->where('is_active', true);
    }

    public function name(string $lang = 'es'): string
    {
        return $this->{'name_' . $lang} ?: $this->name_es;
    }

    public function description(string $lang = 'es'): ?string
    {
        return $this->{'description_' . $lang} ?: $this->description_es;
    }
}
