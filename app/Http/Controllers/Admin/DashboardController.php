<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessHour;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\SiteSetting;
use App\Support\Site;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'counts' => [
                'categories' => MenuCategory::count(),
                'items'      => MenuItem::count(),
                'settings'   => SiteSetting::count(),
                'hours'      => BusinessHour::count(),
            ],
            'isOpenNow'    => Site::isOpenNow(),
            'todaySummary' => Site::todaySummary('es'),
        ]);
    }
}
