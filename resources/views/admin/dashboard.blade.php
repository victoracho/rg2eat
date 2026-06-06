@extends('layouts.admin')
@section('title', 'Inicio')

@section('body')
<div class="page-head">
  <div>
    <div class="crumbs">Panel</div>
    <h1>Bienvenido, {{ auth()->user()?->name ?? 'amig@' }} 🌮</h1>
  </div>
  <div class="row-actions">
    <a class="btn ghost" href="{{ route('home') }}" target="_blank">↗ Ver landing</a>
    <a class="btn" href="{{ route('menu') }}" target="_blank">↗ Ver menú (QR)</a>
  </div>
</div>

<div class="grid-3">
  <div class="card">
    <div class="crumbs">Estado actual</div>
    <h2 style="font-family:'Playfair Display',serif;font-size:1.6rem;margin:.3rem 0 .4rem;">
      {{ $isOpenNow ? '🟢 Abierto ahora' : '🔴 Cerrado ahora' }}
    </h2>
    <p style="font-size:.88rem;color:#666;">{{ $todaySummary }}</p>
    <p style="margin-top:.8rem;"><a href="{{ route('admin.hours.index') }}">Editar horarios →</a></p>
  </div>
  <div class="card">
    <div class="crumbs">Menú</div>
    <h2 style="font-family:'Playfair Display',serif;font-size:1.6rem;margin:.3rem 0 .4rem;">
      {{ $counts['items'] }} platos · {{ $counts['categories'] }} categorías
    </h2>
    <p style="font-size:.88rem;color:#666;">Gestiona las categorías y cada plato individualmente.</p>
    <p style="margin-top:.8rem;"><a href="{{ route('admin.items.index') }}">Gestionar platos →</a></p>
  </div>
  <div class="card">
    <div class="crumbs">Contenido</div>
    <h2 style="font-family:'Playfair Display',serif;font-size:1.6rem;margin:.3rem 0 .4rem;">
      {{ $counts['settings'] }} textos
    </h2>
    <p style="font-size:.88rem;color:#666;">Edita textos, imágenes, redes y mapa de la landing.</p>
    <p style="margin-top:.8rem;"><a href="{{ route('admin.settings.index') }}">Editar contenido →</a></p>
  </div>
</div>

<div class="card">
  <div class="crumbs">Tips</div>
  <ul style="margin:.5rem 0 0 1rem; font-size:.9rem; color:#444; line-height:1.7;">
    <li>El QR de la sección «Menú» de la landing apunta a <code>/menu</code>, así que cualquier cambio en los platos se ve <strong>al instante</strong> al escanearlo.</li>
    <li>El badge «Abierto / Cerrado» se calcula del horario actual respecto a la zona horaria configurada ({{ config('app.timezone') }}).</li>
    <li>Los textos soportan tres idiomas (ES/EN/PT). Si dejas EN o PT vacíos, se mostrará el ES como fallback.</li>
  </ul>
</div>
@endsection
