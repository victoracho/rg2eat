@extends('layouts.admin')
@section('title', 'Editar contenido')

@section('body')
<div class="page-head">
  <div>
    <div class="crumbs">CMS / Contenido</div>
    <h1>{{ $setting->label }}</h1>
    <p style="color:#888;font-size:.82rem;font-family:monospace;">{{ $setting->key }}</p>
  </div>
  <a class="btn secondary" href="{{ route('admin.settings.index') }}">← Volver</a>
</div>

<form method="POST" action="{{ route('admin.settings.update', $setting) }}" class="card">
  @csrf
  @method('PUT')

  <div data-lang-tabs>
    <div class="lang-tabs">
      <button type="button" class="lang-tab active" data-lang="es">ES</button>
      <button type="button" class="lang-tab"        data-lang="en">EN</button>
      <button type="button" class="lang-tab"        data-lang="pt">PT</button>
    </div>

    @foreach (['es' => 'Español', 'en' => 'Inglés (English)', 'pt' => 'Portugués (Português)'] as $code => $name)
      <div class="lang-pane {{ $code === 'es' ? 'active' : '' }}" data-lang="{{ $code }}">
        <div class="field">
          <label>Valor — {{ $name }}</label>
          @if(in_array($setting->type, ['textarea', 'html']))
            <textarea name="value_{{ $code }}" rows="{{ $setting->type === 'html' ? 6 : 4 }}">{{ old('value_' . $code, $setting->{'value_' . $code}) }}</textarea>
            @if($setting->type === 'html')
              <p class="hint">Soporta HTML simple: &lt;br&gt;, &lt;em&gt;, &lt;strong&gt;…</p>
            @endif
          @elseif($setting->type === 'url')
            <input type="url" name="value_{{ $code }}" value="{{ old('value_' . $code, $setting->{'value_' . $code}) }}">
          @else
            <input type="text" name="value_{{ $code }}" value="{{ old('value_' . $code, $setting->{'value_' . $code}) }}">
          @endif
        </div>
      </div>
    @endforeach
  </div>

  <p class="hint">Si dejas EN/PT vacíos, en la web aparecerá el valor en ES como fallback.</p>

  <div class="row-actions" style="margin-top:1rem;">
    <button class="btn" type="submit">💾 Guardar cambios</button>
    <a class="btn ghost" href="{{ route('admin.settings.index') }}">Cancelar</a>
  </div>
</form>
@endsection
