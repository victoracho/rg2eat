<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Admin') · RG2 Eat CMS</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
  :root { --coral:#F38067; --cream:#FEF2EC; --black:#000; --coral-dark:#d9644d; --coral-light:#f9c4b5; --bg:#f7f5f3; --border:#e3dfdb; }
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family:'DM Sans',sans-serif; background:var(--bg); color:#222; }
  a { color:var(--coral); text-decoration:none; }
  a:hover { color:var(--coral-dark); }

  .layout { display:grid; grid-template-columns:240px 1fr; min-height:100vh; }
  aside {
    background:#1c1916; color:#cbc4be; padding:1.4rem 1rem; display:flex; flex-direction:column;
  }
  aside .brand { color:#fff; font-family:'Playfair Display',serif; font-size:1.4rem; font-weight:900; padding:.5rem .5rem 1.4rem; border-bottom:1px solid #2a2520; margin-bottom:1rem; }
  aside .brand small { display:block; font-family:'DM Sans',sans-serif; font-size:.65rem; font-weight:400; text-transform:uppercase; letter-spacing:.18em; color:var(--coral); margin-top:.25rem; }
  aside nav a {
    display:block; color:#cbc4be; padding:.6rem .8rem; border-radius:3px; font-size:.92rem;
    margin-bottom:.15rem; transition:background .15s, color .15s;
  }
  aside nav a:hover, aside nav a.active { background:rgba(243,128,103,.15); color:#fff; }
  aside .spacer { flex:1; }
  aside .who { font-size:.78rem; color:#857d75; padding:.5rem .8rem 0; border-top:1px solid #2a2520; }
  aside form { padding:.4rem .8rem 0; }
  aside button.logout {
    background:none; border:none; color:#cbc4be; font-size:.82rem; cursor:pointer; font-family:inherit;
    padding:.4rem 0; transition:color .15s;
  }
  aside button.logout:hover { color:var(--coral); }

  main { padding:2rem 2.5rem; max-width:1100px; }
  .page-head { display:flex; align-items:flex-end; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.6rem; }
  .page-head h1 { font-family:'Playfair Display',serif; font-size:1.8rem; }
  .page-head .crumbs { font-size:.78rem; text-transform:uppercase; letter-spacing:.12em; color:#888; margin-bottom:.25rem; }
  .btn {
    display:inline-block; background:var(--coral); color:#fff !important; padding:.55rem 1.1rem;
    border-radius:3px; font-size:.82rem; text-transform:uppercase; letter-spacing:.08em; font-weight:500;
    border:none; cursor:pointer; transition:background .15s;
  }
  .btn:hover { background:var(--coral-dark); }
  .btn.secondary { background:#444; }
  .btn.secondary:hover { background:#222; }
  .btn.danger { background:#a83232; }
  .btn.danger:hover { background:#7a1f1f; }
  .btn.ghost { background:transparent; color:var(--coral) !important; border:1.5px solid var(--coral); }
  .btn.ghost:hover { background:var(--coral); color:#fff !important; }
  .btn.sm { padding:.35rem .75rem; font-size:.74rem; }

  .card {
    background:#fff; border:1px solid var(--border); border-radius:6px; padding:1.5rem;
    margin-bottom:1.4rem; box-shadow:0 1px 2px rgba(0,0,0,.03);
  }
  .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:1.4rem; }
  .grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; }
  @media (max-width:780px) { .grid-2,.grid-3 { grid-template-columns:1fr; } }

  table { width:100%; border-collapse:collapse; background:#fff; border:1px solid var(--border); border-radius:6px; overflow:hidden; }
  table th, table td { padding:.7rem .9rem; text-align:left; font-size:.9rem; border-bottom:1px solid var(--border); vertical-align:middle; }
  table th { background:#faf6f3; font-weight:500; text-transform:uppercase; letter-spacing:.06em; font-size:.72rem; color:#555; }
  table tr:last-child td { border-bottom:none; }
  table tr:hover td { background:#fff8f4; }
  .badge { display:inline-block; padding:.18rem .55rem; font-size:.7rem; border-radius:2px; font-weight:500; text-transform:uppercase; letter-spacing:.06em; }
  .badge.on  { background:#dff5e6; color:#1c5d34; }
  .badge.off { background:#f0e0dc; color:#7a1f1f; }
  .badge.tag { background:rgba(243,128,103,.15); color:var(--coral-dark); }

  label { display:block; font-size:.72rem; text-transform:uppercase; letter-spacing:.1em; font-weight:500; color:#333; margin-bottom:.35rem; }
  input[type=text], input[type=email], input[type=password], input[type=url], input[type=number], input[type=time], select, textarea {
    width:100%; padding:.6rem .8rem; border:1.5px solid #ddd; border-radius:3px; font-size:.9rem;
    font-family:inherit; background:#fff; transition:border-color .15s;
  }
  input:focus, textarea:focus, select:focus { outline:none; border-color:var(--coral); }
  textarea { resize:vertical; min-height:90px; font-family:inherit; }
  .field { margin-bottom:1rem; }
  .field .hint { font-size:.72rem; color:#888; margin-top:.25rem; }
  .checkbox { display:flex; align-items:center; gap:.5rem; font-size:.9rem; color:#333; }
  .checkbox input { width:auto; }

  .flash { padding:.85rem 1.2rem; border-radius:4px; margin-bottom:1.2rem; font-size:.9rem; }
  .flash.ok  { background:#dff5e6; color:#1c5d34; border:1px solid #b7e3c2; }
  .flash.err { background:#fbe1dc; color:#7a1f1f; border:1px solid #f1bcb1; }
  .err-list { margin:.5rem 0 0; padding-left:1.2rem; font-size:.82rem; }

  .row-actions { display:flex; gap:.45rem; flex-wrap:wrap; }
  .lang-tabs { display:flex; gap:.25rem; margin-bottom:.8rem; }
  .lang-tab { padding:.3rem .8rem; font-size:.72rem; text-transform:uppercase; letter-spacing:.08em; border:1.5px solid var(--border); background:#fff; border-radius:2px; cursor:pointer; }
  .lang-tab.active { border-color:var(--coral); color:var(--coral); }
  .lang-pane { display:none; }
  .lang-pane.active { display:block; }
  @media (max-width:780px) {
    .layout { grid-template-columns:1fr; }
    aside { flex-direction:row; align-items:center; padding:.8rem 1rem; overflow-x:auto; }
    aside .brand { border:none; padding:0; margin:0 1rem 0 0; }
    aside nav { display:flex; gap:.2rem; flex-wrap:nowrap; }
    aside .spacer, aside .who { display:none; }
    main { padding:1.2rem; }
  }
</style>
@stack('head')
</head>
<body>
<div class="layout">
  <aside>
    <div class="brand">RG2 Eat<small>CMS</small></div>
    <nav>
      @php $r = request()->route()?->getName() ?? ''; @endphp
      <a href="{{ route('admin.dashboard') }}"        class="{{ $r==='admin.dashboard' ? 'active' : '' }}">📊 Inicio</a>
      <a href="{{ route('admin.categories.index') }}" class="{{ str_starts_with($r,'admin.categories') ? 'active' : '' }}">📂 Categorías</a>
      <a href="{{ route('admin.items.index') }}"      class="{{ str_starts_with($r,'admin.items') ? 'active' : '' }}">🍽️ Platos</a>
      <a href="{{ route('admin.hours.index') }}"      class="{{ str_starts_with($r,'admin.hours') ? 'active' : '' }}">🕐 Horarios</a>
      <a href="{{ route('admin.settings.index') }}"   class="{{ str_starts_with($r,'admin.settings') ? 'active' : '' }}">⚙️ Contenido</a>
      <a href="{{ route('home') }}" target="_blank">↗ Ver sitio</a>
      <a href="{{ route('menu') }}" target="_blank">↗ Ver menú</a>
    </nav>
    <div class="spacer"></div>
    <div class="who">{{ auth()->user()?->email }}</div>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button class="logout" type="submit">↻ Cerrar sesión</button>
    </form>
  </aside>
  <main>
    @if(session('ok'))    <div class="flash ok">{{ session('ok') }}</div> @endif
    @if(session('err'))   <div class="flash err">{{ session('err') }}</div> @endif
    @if($errors->any())
      <div class="flash err">
        Revisa los campos:
        <ul class="err-list">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    @yield('body')
  </main>
</div>

<script>
  // Simple lang-tab switcher
  document.querySelectorAll('[data-lang-tabs]').forEach(group => {
    const tabs  = group.querySelectorAll('.lang-tab');
    const panes = group.querySelectorAll('.lang-pane');
    tabs.forEach(t => t.addEventListener('click', e => {
      e.preventDefault();
      const code = t.dataset.lang;
      tabs.forEach(x  => x.classList.toggle('active', x.dataset.lang === code));
      panes.forEach(x => x.classList.toggle('active', x.dataset.lang === code));
    }));
  });
</script>
@stack('scripts')
</body>
</html>
