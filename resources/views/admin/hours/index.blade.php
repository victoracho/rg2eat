@extends('layouts.admin')
@section('title', 'Horarios')

@section('body')
@php
  $dayNames = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
@endphp

<div class="page-head">
  <div><div class="crumbs">CMS</div><h1>Horarios de apertura</h1></div>
</div>

<p style="color:#666;font-size:.92rem;margin-bottom:1.4rem;">
  Cada día puede tener varias franjas (p. ej. <em>lunch</em> y <em>dinner</em>). Marca <strong>Cerrado</strong> si ese día no abres. El badge «Abierto hoy» de la landing se calcula desde aquí.
</p>

@foreach ($dayNames as $dow => $name)
  <div class="card">
    <h2 style="font-family:'Playfair Display',serif;font-size:1.2rem;margin-bottom:.8rem;">{{ $name }}</h2>

    @if (empty($grouped[$dow]))
      <p style="color:#888;font-size:.88rem;margin-bottom:.6rem;">Sin franjas. Añade una abajo.</p>
    @else
      <table style="margin-bottom:.8rem;">
        <thead><tr><th>Franja</th><th>Abre</th><th>Cierra</th><th>Cerrado</th><th></th></tr></thead>
        <tbody>
          @foreach ($grouped[$dow] as $h)
            <tr>
              <form method="POST" action="{{ route('admin.hours.update', $h) }}">
                @csrf @method('PUT')
                <td>
                  <select name="shift">
                    <option value="lunch"  @selected($h->shift==='lunch')>Lunch / Almuerzo</option>
                    <option value="dinner" @selected($h->shift==='dinner')>Dinner / Cena</option>
                    <option value="full"   @selected($h->shift==='full')>Servicio completo</option>
                  </select>
                </td>
                <td><input type="time" name="open_time"  value="{{ $h->open_time  ? substr($h->open_time, 0, 5) : '' }}"></td>
                <td><input type="time" name="close_time" value="{{ $h->close_time ? substr($h->close_time, 0, 5) : '' }}"></td>
                <td><label class="checkbox"><input type="checkbox" name="is_closed" value="1" @checked($h->is_closed)> Cerrado</label></td>
                <td>
                  <div class="row-actions">
                    <button class="btn sm" type="submit">💾</button>
              </form>
              <form method="POST" action="{{ route('admin.hours.destroy', $h) }}" onsubmit="return confirm('¿Eliminar franja?');" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn sm danger" type="submit">✕</button>
              </form>
                  </div>
                </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif

    <form method="POST" action="{{ route('admin.hours.store') }}" style="display:flex;gap:.5rem;flex-wrap:wrap;align-items:flex-end;">
      @csrf
      <input type="hidden" name="day_of_week" value="{{ $dow }}">
      <div><label>Franja</label>
        <select name="shift">
          <option value="lunch">Lunch</option>
          <option value="dinner">Dinner</option>
          <option value="full">Servicio</option>
        </select>
      </div>
      <div><label>Abre</label><input type="time" name="open_time"></div>
      <div><label>Cierra</label><input type="time" name="close_time"></div>
      <label class="checkbox"><input type="checkbox" name="is_closed" value="1"> Cerrado</label>
      <button class="btn sm" type="submit">＋ Añadir</button>
    </form>
  </div>
@endforeach
@endsection
