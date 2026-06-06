<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MenuItemController extends Controller
{
    public function index(Request $request)
    {
        $items = MenuItem::query()
            ->with('category')
            ->when($request->query('cat'), fn ($q, $cat) => $q->where('menu_category_id', $cat))
            ->orderBy('menu_category_id')->orderBy('sort_order')
            ->get();

        return view('admin.items.index', [
            'items'      => $items,
            'categories' => MenuCategory::orderBy('sort_order')->get(),
            'filterCat'  => $request->query('cat'),
        ]);
    }

    public function create()
    {
        return view('admin.items.edit', [
            'item'       => new MenuItem(['currency' => 'EUR', 'is_active' => true]),
            'categories' => MenuCategory::orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image_path'] = $this->handleImage($request, null);
        $item = MenuItem::create($data);
        return redirect()->route('admin.items.index')
            ->with('ok', "Plato «{$item->name_es}» creado.");
    }

    public function edit(MenuItem $item)
    {
        return view('admin.items.edit', [
            'item'       => $item,
            'categories' => MenuCategory::orderBy('sort_order')->get(),
        ]);
    }

    public function update(Request $request, MenuItem $item)
    {
        $data = $this->validated($request);

        if ($request->boolean('remove_image') && $item->image_path) {
            Storage::disk('public')->delete($item->image_path);
            $data['image_path'] = null;
        }
        if ($request->hasFile('image')) {
            if ($item->image_path) {
                Storage::disk('public')->delete($item->image_path);
            }
            $data['image_path'] = $this->handleImage($request, $item);
        }

        $item->update($data);
        return redirect()->route('admin.items.index')
            ->with('ok', "Plato «{$item->name_es}» actualizado.");
    }

    public function destroy(MenuItem $item)
    {
        $name = $item->name_es;
        if ($item->image_path) {
            Storage::disk('public')->delete($item->image_path);
        }
        $item->delete();
        return redirect()->route('admin.items.index')
            ->with('ok', "Plato «{$name}» eliminado.");
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'menu_category_id' => ['required', 'exists:menu_categories,id'],
            'name_es'          => ['required', 'string', 'max:160'],
            'name_en'          => ['nullable', 'string', 'max:160'],
            'name_pt'          => ['nullable', 'string', 'max:160'],
            'description_es'   => ['nullable', 'string'],
            'description_en'   => ['nullable', 'string'],
            'description_pt'   => ['nullable', 'string'],
            'price'            => ['required', 'numeric', 'min:0'],
            'currency'         => ['nullable', 'string', 'size:3'],
            'tags'             => ['nullable', 'string'],
            'is_active'        => ['sometimes', 'boolean'],
            'is_featured'      => ['sometimes', 'boolean'],
            'sort_order'       => ['nullable', 'integer', 'min:0'],
            'image'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_image'     => ['sometimes', 'boolean'],
        ]);
        unset($data['image'], $data['remove_image']);

        $data['is_active']   = (bool) ($data['is_active']   ?? false);
        $data['is_featured'] = (bool) ($data['is_featured'] ?? false);
        $data['currency']    = strtoupper($data['currency'] ?? 'EUR');
        $data['sort_order']  = $data['sort_order'] ?? 0;
        $data['tags']        = $this->parseTags($data['tags'] ?? null);

        return $data;
    }

    private function handleImage(Request $request, ?MenuItem $item): ?string
    {
        if (!$request->hasFile('image')) {
            return $item?->image_path;
        }
        $file = $request->file('image');
        $base = Str::slug($request->input('name_es', 'plato'));
        $ext  = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $name = $base . '-' . Str::random(8) . '.' . $ext;
        return $file->storeAs('menu', $name, 'public');
    }

    private function parseTags(?string $raw): array
    {
        if (!$raw) {
            return [];
        }
        return collect(explode(',', $raw))
            ->map(fn ($t) => strtolower(trim($t)))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
