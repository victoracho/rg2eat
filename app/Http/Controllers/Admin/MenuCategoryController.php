<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MenuCategoryController extends Controller
{
    public function index()
    {
        $categories = MenuCategory::withCount('items')->orderBy('sort_order')->get();
        return view('admin.categories.index', ['categories' => $categories]);
    }

    public function create()
    {
        return view('admin.categories.edit', ['category' => new MenuCategory()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, null);
        $cat = MenuCategory::create($data);
        return redirect()->route('admin.categories.index')
            ->with('ok', "Categoría «{$cat->name_es}» creada.");
    }

    public function edit(MenuCategory $category)
    {
        return view('admin.categories.edit', ['category' => $category]);
    }

    public function update(Request $request, MenuCategory $category)
    {
        $data = $this->validated($request, $category->id);
        $category->update($data);
        return redirect()->route('admin.categories.index')
            ->with('ok', "Categoría «{$category->name_es}» actualizada.");
    }

    public function destroy(MenuCategory $category)
    {
        $name = $category->name_es;
        $category->delete();
        return redirect()->route('admin.categories.index')
            ->with('ok', "Categoría «{$name}» eliminada.");
    }

    private function validated(Request $request, ?int $ignoreId): array
    {
        $data = $request->validate([
            'slug'           => ['nullable', 'string', 'max:80', Rule::unique('menu_categories', 'slug')->ignore($ignoreId)],
            'icon'           => ['nullable', 'string', 'max:16'],
            'name_es'        => ['required', 'string', 'max:120'],
            'name_en'        => ['nullable', 'string', 'max:120'],
            'name_pt'        => ['nullable', 'string', 'max:120'],
            'description_es' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'description_pt' => ['nullable', 'string'],
            'is_active'      => ['sometimes', 'boolean'],
            'sort_order'     => ['nullable', 'integer', 'min:0'],
        ]);
        $data['is_active']  = (bool) ($data['is_active'] ?? false);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['slug']       = $data['slug'] ?: Str::slug($data['name_es']);

        return $data;
    }
}
