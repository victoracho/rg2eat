<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_category_id',
        'name_es', 'name_en', 'name_pt',
        'description_es', 'description_en', 'description_pt',
        'price', 'price_2', 'currency', 'image_path',
        'is_active', 'is_featured', 'tags', 'sort_order',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'is_featured' => 'boolean',
        'price'       => 'decimal:2',
        'price_2'     => 'decimal:2',
        'tags'        => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }

    public function name(string $lang = 'es'): string
    {
        return $this->{'name_' . $lang} ?: $this->name_es;
    }

    public function description(string $lang = 'es'): ?string
    {
        return $this->{'description_' . $lang} ?: $this->description_es;
    }

    public function imageUrl(): ?string
    {
        if (!$this->image_path) {
            return null;
        }
        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }
        return Storage::disk('public')->url($this->image_path);
    }

    public function formattedPrice(?string $value = null): string
    {
        $value = func_num_args() ? $value : $this->price;
        if ($value === null) {
            return '';
        }
        $sym = match (strtoupper($this->currency)) {
            'EUR' => '€',
            'USD' => '$',
            'GBP' => '£',
            default => $this->currency . ' ',
        };
        // €4 en vez de €4,00, como en la carta impresa
        $num = fmod((float) $value, 1.0) == 0.0 ? number_format((float) $value, 0) : number_format((float) $value, 2, ',', '.');
        return $sym . $num;
    }
}
