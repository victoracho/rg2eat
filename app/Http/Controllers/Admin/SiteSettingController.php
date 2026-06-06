<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function index()
    {
        $rows = SiteSetting::orderBy('group')->orderBy('sort_order')->get()->groupBy('group');
        return view('admin.settings.index', ['groups' => $rows]);
    }

    public function edit(SiteSetting $setting)
    {
        return view('admin.settings.edit', ['setting' => $setting]);
    }

    public function update(Request $request, SiteSetting $setting)
    {
        $data = $request->validate([
            'value_es' => ['nullable', 'string'],
            'value_en' => ['nullable', 'string'],
            'value_pt' => ['nullable', 'string'],
        ]);
        $setting->update($data);

        return redirect()->route('admin.settings.index')->with('ok', 'Contenido guardado.');
    }
}
