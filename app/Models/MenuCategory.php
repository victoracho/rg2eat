<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuCategory extends Model
{
    public const SECTIONS = ['food' => 'Comida', 'drinks' => 'Bebidas'];

    // Estilos sacados de la carta impresa (fotos/). Se pintan en public/menu.blade.php.
    public const STYLES = [
        'dotted-red'   => 'Recuadro punteado rojo (First time, Los Chilangos)',
        'dotted-black' => 'Recuadro punteado negro (Los Chingones)',
        'tab-blue'     => 'Etiqueta azul (Burritos, Chilaquiles)',
        'tab-yellow'   => 'Etiqueta amarilla (To start, Gringas)',
        'tab-red'      => 'Etiqueta roja (Base, Campechanos)',
        'title-red'    => 'Título rojo sin fondo (Vodka, Ron, Tequila)',
        'brush-yellow' => 'Brochazo amarillo (Cervezas, Chelas)',
        'brush-aqua'   => 'Brochazo aqua (Aguas frescas, Refrescos)',
        'box-black'    => 'Recuadro negro (Experiencias)',
        'fill-orange'  => 'Caja naranja rellena (Órdenes chilangos)',
        'fill-blue'    => 'Caja azul rellena (Órdenes chingones)',
    ];

    protected $fillable = [
        'parent_id', 'slug', 'section', 'style', 'price_label', 'price_2_label', 'icon',
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

    public function activeChildren(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->where('is_active', true)->orderBy('sort_order');
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
