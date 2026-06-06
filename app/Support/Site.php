<?php

namespace App\Support;

use App\Models\BusinessHour;
use App\Models\SiteSetting;
use Carbon\CarbonImmutable;

/**
 * Façade-style helper for views and controllers.
 */
class Site
{
    public static function lang(): string
    {
        $supported = ['es', 'en', 'pt'];
        $q = request()->query('lang');
        if ($q && in_array($q, $supported, true)) {
            return $q;
        }
        $browser = strtolower(substr((string) request()->header('Accept-Language'), 0, 2));
        if (in_array($browser, $supported, true)) {
            return $browser;
        }
        return config('app.locale', 'es');
    }

    public static function setting(string $key, ?string $lang = null): string
    {
        $lang = $lang ?: static::lang();
        $bag  = SiteSetting::bag();
        if (!isset($bag[$key])) {
            return '';
        }
        return (string) ($bag[$key][$lang] ?? $bag[$key]['es'] ?? '');
    }

    public static function isOpenNow(): bool
    {
        return BusinessHour::isOpenNow(CarbonImmutable::now(config('app.timezone')));
    }

    /**
     * Localized today summary, e.g. "Lunes · 12:00–15:00 · 18:00–23:00" or "Hoy cerrado".
     */
    public static function todaySummary(?string $lang = null): string
    {
        $lang = $lang ?: static::lang();
        $now  = CarbonImmutable::now(config('app.timezone'));
        $dow  = (int) $now->dayOfWeek;
        $name = BusinessHour::dayNames($lang)[$dow] ?? '';

        $rows = BusinessHour::where('day_of_week', $dow)
            ->orderBy('sort_order')
            ->get();

        if ($rows->isEmpty() || $rows->every(fn ($r) => $r->is_closed)) {
            $closedWord = match ($lang) {
                'en' => 'closed today',
                'pt' => 'fechado hoje',
                default => 'cerrado hoy',
            };
            return $name . ' · ' . $closedWord;
        }

        $parts = [];
        foreach ($rows as $row) {
            if ($row->is_closed || !$row->open_time || !$row->close_time) {
                continue;
            }
            $parts[] = substr($row->open_time, 0, 5) . '–' . substr($row->close_time, 0, 5);
        }
        return $name . ' · ' . implode(' · ', $parts);
    }
}
