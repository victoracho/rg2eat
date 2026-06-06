<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessHour;
use Illuminate\Http\Request;

class BusinessHourController extends Controller
{
    public function index()
    {
        $grouped = BusinessHour::groupedByDay();
        return view('admin.hours.index', ['grouped' => $grouped]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'day_of_week' => ['required', 'integer', 'min:0', 'max:6'],
            'shift'       => ['required', 'string', 'in:lunch,dinner,full'],
            'open_time'   => ['nullable', 'date_format:H:i'],
            'close_time'  => ['nullable', 'date_format:H:i'],
            'is_closed'   => ['sometimes', 'boolean'],
        ]);
        $data['is_closed']  = (bool) ($data['is_closed'] ?? false);
        $data['sort_order'] = BusinessHour::where('day_of_week', $data['day_of_week'])->count();

        BusinessHour::create($data);

        return back()->with('ok', 'Franja añadida.');
    }

    public function update(Request $request, BusinessHour $hour)
    {
        $data = $request->validate([
            'shift'      => ['required', 'string', 'in:lunch,dinner,full'],
            'open_time'  => ['nullable', 'date_format:H:i'],
            'close_time' => ['nullable', 'date_format:H:i'],
            'is_closed'  => ['sometimes', 'boolean'],
        ]);
        $data['is_closed'] = (bool) ($data['is_closed'] ?? false);
        $hour->update($data);

        return back()->with('ok', 'Franja actualizada.');
    }

    public function destroy(BusinessHour $hour)
    {
        $hour->delete();
        return back()->with('ok', 'Franja eliminada.');
    }
}
