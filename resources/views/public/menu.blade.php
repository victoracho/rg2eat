@extends('layouts.public')

@php
  $S = \App\Support\Site::class;
  $sectionNames = [
    'food'   => ['es' => 'la carta', 'en' => 'the menu', 'pt' => 'a carta'],
    'drinks' => ['es' => 'bebidas',  'en' => 'drinks',   'pt' => 'bebidas'],
  ];
  $sections = $categories->groupBy(fn ($c) => $c->section ?: 'food');
@endphp

@section('title', $S::setting('menu_title') . ' · ' . $S::setting('brand_name'))

@push('head')
<style>
  .menu-page { padding:7rem 2.5rem 4rem; max-width:1400px; margin:0 auto; font-family:'Poppins',sans-serif; }
  .menu-head { text-align:center; }
  .menu-head h1 { font-size:clamp(2.6rem,6vw,4.2rem); }
  .menu-page .status {
    display:inline-block; padding:.35rem .9rem; border-radius:2px; font-size:.72rem;
    text-transform:uppercase; letter-spacing:.12em; font-weight:500; margin:1rem 0 .4rem;
  }
  .menu-page .status.open   { background:#0e7a3a; color:#fff; }
  .menu-page .status.closed { background:#6b1f1f; color:#fff; }
  .menu-page .today { color:#666; font-size:.88rem; }
  .jump { display:flex; gap:.8rem; justify-content:center; margin-top:1.4rem; }
  .jump a { font-family:'Bebas Neue',sans-serif; font-size:1.3rem; letter-spacing:.06em; text-decoration:none; padding:.3rem 1.2rem; background:var(--blue); color:#fff; transform:rotate(-2deg); }
  .jump a:nth-child(2) { background:var(--red); transform:rotate(2deg); }

  @media (max-width:760px) {
    .menu-page { padding:6rem 1rem 3rem; }
  }
</style>
@endpush

@section('body')
<div class="menu-page">
  <header class="menu-head">
    <h1 class="script">{{ $S::setting('menu_title') }}</h1>
    <div class="status {{ $isOpenNow ? 'open' : 'closed' }}">
      {{ $isOpenNow ? $S::setting('hero_badge_line1') : $S::setting('hero_badge_closed_line1') }}
    </div>
    <p class="today">{{ $todaySummary }}</p>
    @if($sections->count() > 1)
      <nav class="jump">
        @foreach($sections as $key => $_)
          <a href="#{{ $key }}">{{ $sectionNames[$key][$lang] ?? $key }}</a>
        @endforeach
      </nav>
    @endif
  </header>

  @include('public.partials.menu-groups')
</div>
@endsection
