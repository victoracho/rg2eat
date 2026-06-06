@extends('layouts.admin')
@section('title', $category->exists ? 'Editar categoría' : 'Nueva categoría')

@php $action = $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store'); @endphp

@section('body')
<div class="page-head">
  <div>
    <div class="crumbs">CMS / Menú / Categorías</div>
    <h1>{{ $category->exists ? 'Editar categoría' : 'Nueva categoría' }}</h1>
  </div>
  <a class="btn secondary" href="{{ route('admin.categories.index') }}">← Volver</a>
</div>

<form method="POST" action="{{ $action }}" class="card">
  @csrf
  @if($category->exists) @method('PUT') @endif

  <div class="grid-3">
    <div class="field">
      <label>Icono (emoji)</label>
      <input type="text" name="icon" maxlength="16" value="{{ old('icon', $category->icon) }}">
      <p class="hint">Ej: ☀️, 🌙, 🍹, 🌮</p>
    </div>
    <div class="field">
      <label>Slug (URL)</label>
      <input type="text" name="slug" value="{{ old('slug', $category->slug) }}">
      <p class="hint">Si lo dejas vacío, se genera del nombre.</p>
    </div>
    <div class="field">
      <label>Orden</label>
      <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $category->sort_order ?? 0) }}">
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
          <input type="text" name="name_{{ $code }}" value="{{ old('name_' . $code, $category->{'name_' . $code}) }}" {{ $code === 'es' ? 'required' : '' }}>
        </div>
        <div class="field">
          <label>Descripción ({{ strtoupper($code) }})</label>
          <textarea name="description_{{ $code }}" rows="3">{{ old('description_' . $code, $category->{'description_' . $code}) }}</textarea>
        </div>
      </div>
    @endforeach
  </div>

  <label class="checkbox" style="margin:.5rem 0 1rem;">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active ?? true))>
    Categoría activa (visible en web)
  </label>

  <div class="row-actions">
    <button class="btn" type="submit">💾 Guardar</button>
    <a class="btn ghost" href="{{ route('admin.categories.index') }}">Cancelar</a>
  </div>
</form>
@endsection
