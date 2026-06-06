@extends('layouts.admin')
@section('title', 'Contenido')

@section('body')
<div class="page-head">
  <div><div class="crumbs">CMS</div><h1>Contenido del sitio</h1></div>
</div>

@php
  $groupLabels = [
    'general'  => '🪪 Marca / SEO',
    'nav'      => '🧭 Navegación',
    'hero'     => '🌮 Hero',
    'menu'     => '📜 Sección Menú',
    'about'    => '📖 Sobre nosotros',
    'social'   => '📲 Redes sociales',
    'location' => '📍 Ubicación / Mapa',
    'footer'   => '👣 Footer',
  ];
@endphp

@foreach($groupLabels as $key => $title)
  @if($groups->has($key))
    <div class="card">
      <h2 style="font-family:'Playfair Display',serif;font-size:1.3rem;margin-bottom:.8rem;">{{ $title }}</h2>
      <table>
        <thead>
          <tr>
            <th style="width:38%;">Campo</th>
            <th>Valor (ES)</th>
            <th style="width:120px;">Tipo</th>
            <th style="width:80px;"></th>
          </tr>
        </thead>
        <tbody>
          @foreach($groups[$key] as $s)
            <tr>
              <td>{{ $s->label }}<br><span style="color:#888;font-size:.72rem;font-family:monospace;">{{ $s->key }}</span></td>
              <td><div style="max-width:520px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#444;">{{ \Illuminate\Support\Str::limit(strip_tags((string) $s->value_es), 100) }}</div></td>
              <td><span class="badge tag">{{ $s->type }}</span></td>
              <td><a class="btn sm ghost" href="{{ route('admin.settings.edit', $s) }}">Editar</a></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
@endforeach
@endsection
