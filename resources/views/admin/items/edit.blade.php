@extends('layouts.admin')
@section('title', $item->exists ? 'Editar plato' : 'Nuevo plato')

@php $action = $item->exists ? route('admin.items.update', $item) : route('admin.items.store'); @endphp

@section('body')
<div class="page-head">
  <div>
    <div class="crumbs">CMS / Menú / Platos</div>
    <h1>{{ $item->exists ? 'Editar plato' : 'Nuevo plato' }}</h1>
  </div>
  <a class="btn secondary" href="{{ route('admin.items.index') }}">← Volver</a>
</div>

<form method="POST" action="{{ $action }}" class="card" enctype="multipart/form-data">
  @csrf
  @if($item->exists) @method('PUT') @endif

  <div class="grid-3">
    <div class="field">
      <label>Categoría <span style="color:#c33;">*</span></label>
      <select name="menu_category_id" required>
        @foreach($categories as $c)
          <option value="{{ $c->id }}" @selected(old('menu_category_id', $item->menu_category_id) == $c->id)>{{ $c->icon }} {{ $c->name_es }}</option>
        @endforeach
      </select>
    </div>
    <div class="field">
      <label>Precio <span style="color:#c33;">*</span></label>
      <input type="number" name="price" step="0.01" min="0" value="{{ old('price', $item->price ?? 0) }}" required>
    </div>
    <div class="field">
      <label>Moneda</label>
      <select name="currency">
        @foreach(['EUR','USD','GBP','MXN','BRL'] as $cur)
          <option value="{{ $cur }}" @selected(old('currency', $item->currency ?? 'EUR') === $cur)>{{ $cur }}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div data-lang-tabs>
    <div class="lang-tabs">
      <button type="button" class="lang-tab active" data-lang="es">ES</button>
      <button type="button" class="lang-tab"        data-lang="en">EN</button>
      <button type="button" class="lang-tab"        data-lang="pt">PT</button>
    </div>
    @foreach (['es','en','pt'] as $code)
      <div class="lang-pane {{ $code === 'es' ? 'active' : '' }}" data-lang="{{ $code }}">
        <div class="field">
          <label>Nombre ({{ strtoupper($code) }}) {!! $code === 'es' ? '<span style="color:#c33;">*</span>' : '' !!}</label>
          <input type="text" name="name_{{ $code }}" value="{{ old('name_' . $code, $item->{'name_' . $code}) }}" {{ $code === 'es' ? 'required' : '' }}>
        </div>
        <div class="field">
          <label>Descripción ({{ strtoupper($code) }})</label>
          <textarea name="description_{{ $code }}" rows="3">{{ old('description_' . $code, $item->{'description_' . $code}) }}</textarea>
        </div>
      </div>
    @endforeach
  </div>

  <div class="field">
    <label>Foto del plato</label>
    @if($item->exists && $item->image_path)
      <div style="display:flex;align-items:center;gap:1rem;margin-bottom:.6rem;">
        <img src="{{ $item->imageUrl() }}" alt="" style="width:120px;height:120px;object-fit:cover;border-radius:4px;border:1px solid var(--border);">
        <div>
          <p style="font-size:.78rem;color:#666;margin-bottom:.3rem;font-family:monospace;">{{ $item->image_path }}</p>
          <label class="checkbox" style="font-size:.82rem;">
            <input type="checkbox" name="remove_image" value="1">
            Quitar foto actual al guardar
          </label>
        </div>
      </div>
    @endif
    <input type="file" name="image" accept="image/jpeg,image/png,image/webp">
    <p class="hint">JPG, PNG o WEBP · máximo 4 MB · se guarda en <code>storage/app/public/menu/</code>.</p>
  </div>

  <div class="grid-2">
    <div class="field">
      <label>Tags (separadas por coma)</label>
      <input type="text" name="tags" value="{{ old('tags', is_array($item->tags) ? implode(', ', $item->tags) : '') }}">
      <p class="hint">Ej: <code>vegan, spicy, signature</code></p>
    </div>
    <div class="field">
      <label>Orden</label>
      <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $item->sort_order ?? 0) }}">
    </div>
  </div>

  <div style="display:flex;gap:1.5rem;flex-wrap:wrap;">
    <label class="checkbox">
      <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item->is_active ?? true))>
      Visible en web
    </label>
    <label class="checkbox">
      <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $item->is_featured ?? false))>
      Destacado
    </label>
  </div>

  <div class="row-actions" style="margin-top:1rem;">
    <button class="btn" type="submit">💾 Guardar plato</button>
    <a class="btn ghost" href="{{ route('admin.items.index') }}">Cancelar</a>
  </div>
</form>
@endsection
