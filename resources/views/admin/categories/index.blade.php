@extends('layouts.admin')
@section('title', 'Categorías')

@section('body')
<div class="page-head">
  <div><div class="crumbs">CMS / Menú</div><h1>Categorías</h1></div>
  <a class="btn" href="{{ route('admin.categories.create') }}">＋ Nueva categoría</a>
</div>

<div class="card" style="padding:0;">
  <table>
    <thead>
      <tr>
        <th style="width:60px;">#</th>
        <th>Categoría</th>
        <th>Slug</th>
        <th>Platos</th>
        <th>Activa</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($categories as $cat)
        <tr>
          <td>{{ $cat->sort_order }}</td>
          <td>
            <strong>{{ $cat->icon }} {{ $cat->name_es }}</strong><br>
            <span style="color:#888;font-size:.78rem;">EN: {{ $cat->name_en ?: '—' }} · PT: {{ $cat->name_pt ?: '—' }}</span>
          </td>
          <td><code>{{ $cat->slug }}</code></td>
          <td>{{ $cat->items_count }}</td>
          <td>
            @if($cat->is_active)
              <span class="badge on">Activa</span>
            @else
              <span class="badge off">Oculta</span>
            @endif
          </td>
          <td>
            <div class="row-actions">
              <a class="btn sm ghost" href="{{ route('admin.items.index', ['cat' => $cat->id]) }}">Ver platos</a>
              <a class="btn sm" href="{{ route('admin.categories.edit', $cat) }}">Editar</a>
              <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('¿Eliminar categoría «{{ $cat->name_es }}» y todos sus platos?');" style="display:inline;">
                @csrf @method('DELETE')
                <button class="btn sm danger" type="submit">Eliminar</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="6" style="text-align:center;color:#888;padding:1.5rem;">Sin categorías todavía.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
