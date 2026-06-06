@extends('layouts.admin')
@section('title', 'Platos')

@section('body')
<div class="page-head">
  <div><div class="crumbs">CMS / Menú</div><h1>Platos</h1></div>
  <a class="btn" href="{{ route('admin.items.create') }}">＋ Nuevo plato</a>
</div>

<form method="GET" class="card" style="display:flex;gap:.6rem;align-items:flex-end;">
  <div style="flex:1;">
    <label>Filtrar por categoría</label>
    <select name="cat" onchange="this.form.submit()">
      <option value="">— Todas —</option>
      @foreach($categories as $c)
        <option value="{{ $c->id }}" @selected((int) $filterCat === $c->id)>{{ $c->icon }} {{ $c->name_es }}</option>
      @endforeach
    </select>
  </div>
  @if($filterCat)
    <a class="btn secondary sm" href="{{ route('admin.items.index') }}">Limpiar</a>
  @endif
</form>

<div class="card" style="padding:0;">
  <table>
    <thead>
      <tr>
        <th style="width:60px;">#</th>
        <th style="width:70px;">Foto</th>
        <th>Plato</th>
        <th>Categoría</th>
        <th>Precio</th>
        <th>Estado</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($items as $it)
        <tr>
          <td>{{ $it->sort_order }}</td>
          <td>
            @if($it->imageUrl())
              <img src="{{ $it->imageUrl() }}" alt="" style="width:48px;height:48px;object-fit:cover;border-radius:4px;border:1px solid var(--border);">
            @else
              <div style="width:48px;height:48px;background:#f3eee9;border-radius:4px;display:flex;align-items:center;justify-content:center;color:#bbb;font-size:1.2rem;">—</div>
            @endif
          </td>
          <td>
            <strong>{{ $it->name_es }}</strong>
            @if($it->is_featured) <span class="badge tag">★ Destacado</span> @endif
            <br>
            <span style="color:#777;font-size:.78rem;">{{ \Illuminate\Support\Str::limit($it->description_es, 90) }}</span>
            @if(!empty($it->tags))
              <div style="margin-top:.3rem;">
                @foreach($it->tags as $t) <span class="badge tag">{{ $t }}</span> @endforeach
              </div>
            @endif
          </td>
          <td>{{ $it->category?->icon }} {{ $it->category?->name_es ?? '—' }}</td>
          <td>{{ $it->formattedPrice() }}</td>
          <td>
            @if($it->is_active)
              <span class="badge on">Visible</span>
            @else
              <span class="badge off">Oculto</span>
            @endif
          </td>
          <td>
            <div class="row-actions">
              <a class="btn sm" href="{{ route('admin.items.edit', $it) }}">Editar</a>
              <form method="POST" action="{{ route('admin.items.destroy', $it) }}" onsubmit="return confirm('¿Eliminar plato «{{ $it->name_es }}»?');" style="display:inline;">
                @csrf @method('DELETE')
                <button class="btn sm danger" type="submit">Eliminar</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="7" style="text-align:center;color:#888;padding:1.5rem;">Sin platos.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
