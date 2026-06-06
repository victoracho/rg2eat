<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

class BusinessHour extends Model
{
    protected $fillable = [
        'day_of_week', 'shift', 'open_time', 'close_time', 'is_closed', 'sort_order',
    ];

    protected $casts = [
        'is_closed'   => 'boolean',
        'day_of_week' => 'integer',
    ];

    public static function dayNames(string $lang = 'es'): array
    {
        $map = [
            'es' => ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            'en' => ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            'pt' => ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
        ];
        return $map[$lang] ?? $map['es'];
    }

    public static function isOpenNow(?CarbonImmutable $now = null): bool
    {
        $now = $now ?? CarbonImmutable::now(config('app.timezone'));
        $dow = (int) $now->dayOfWeek;
        $time = $now->format('H:i:s');

        return static::query()
            ->where('day_of_week', $dow)
            ->where('is_closed', false)
            ->whereNotNull('open_time')
            ->whereNotNull('close_time')
            ->where('open_time', '<=', $time)
            ->where('close_time', '>=', $time)
            ->exists();
    }

    /**
     * Group hours by day for display.
     * Returns: [0 => [BusinessHour, BusinessHour], 1 => [...], ...]
     */
    public static function groupedByDay(): array
    {
        $rows = static::orderBy('day_of_week')->orderBy('sort_order')->get();
        $grouped = array_fill(0, 7, []);
        foreach ($rows as $row) {
            $grouped[$row->day_of_week][] = $row;
        }
        return $grouped;
    }

    public function shiftLabel(string $lang = 'es'): string
    {
        $labels = [
            'es' => ['lunch' => 'Almuerzo', 'dinner' => 'Cena', 'full' => 'Servicio'],
            'en' => ['lunch' => 'Lunch',    'dinner' => 'Dinner', 'full' => 'Service'],
            'pt' => ['lunch' => 'Almoço',   'dinner' => 'Jantar', 'full' => 'Serviço'],
        ];
        return $labels[$lang][$this->shift] ?? ucfirst($this->shift);
    }

    public function formatRange(): string
    {
        if ($this->is_closed || !$this->open_time || !$this->close_time) {
            return '—';
        }
        return substr($this->open_time, 0, 5) . ' – ' . substr($this->close_time, 0, 5);
    }
}
