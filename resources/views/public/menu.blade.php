@extends('layouts.public')

@php $S = \App\Support\Site::class; @endphp

@section('title', $S::setting('menu_title') . ' · ' . $S::setting('brand_name'))

@push('head')
<style>
  .menu-page { padding:7rem 4rem 4rem; max-width:1100px; margin:0 auto; }
  .menu-page h1 { font-family:'Playfair Display',serif; font-size:clamp(2.2rem,5vw,3.6rem); font-weight:900; }
  .menu-page .status {
    display:inline-block; padding:.35rem .9rem; border-radius:2px; font-size:.72rem;
    text-transform:uppercase; letter-spacing:.12em; font-weight:500; margin-bottom:1rem;
  }
  .menu-page .status.open   { background:#0e7a3a; color:#fff; }
  .menu-page .status.closed { background:#6b1f1f; color:#fff; }
  .menu-page .today { color:#666; font-size:.92rem; margin-bottom:2.5rem; }
  .cat { margin-top:3.5rem; }
  .cat-head { display:flex; align-items:center; gap:1rem; margin-bottom:1rem; }
  .cat-head .icon { font-size:2rem; }
  .cat-head h2 { font-family:'Playfair Display',serif; font-size:2rem; font-weight:800; color:var(--coral); }
  .cat-desc { color:#555; max-width:640px; margin-bottom:1.8rem; font-weight:300; }
  .items { display:grid; grid-template-columns:1fr 1fr; gap:1.4rem; }
  .item {
    background:#fff; border:1px solid var(--coral-light); border-radius:6px;
    padding:1rem; display:flex; gap:1rem; align-items:flex-start;
    transition:border-color .2s, transform .15s;
  }
  .item:hover { border-color:var(--coral); transform:translateY(-2px); }
  .item .icon { font-size:1.6rem; flex-shrink:0; }
  .item .photo {
    width:96px; height:96px; flex-shrink:0; border-radius:6px; overflow:hidden;
    background:linear-gradient(135deg, var(--coral-light) 0%, #fff8f4 100%);
    display:flex; align-items:center; justify-content:center; font-size:2rem;
  }
  .item .photo img { width:100%; height:100%; object-fit:cover; display:block; }
  .item .body { flex:1; min-width:0; }
  .item .row { display:flex; justify-content:space-between; align-items:baseline; gap:.5rem; }
  .item h3 { font-family:'Playfair Display',serif; font-size:1.15rem; font-weight:700; }
  .item .price { color:var(--coral); font-weight:600; white-space:nowrap; }
  .item .desc { color:#555; font-size:.88rem; line-height:1.5; margin-top:.25rem; font-weight:300; }
  .item .tags { display:flex; gap:.4rem; margin-top:.6rem; flex-wrap:wrap; }
  .item .tag {
    background:rgba(243,128,103,.15); color:var(--coral-dark);
    font-size:.65rem; text-transform:uppercase; letter-spacing:.08em;
    padding:.18rem .5rem; border-radius:2px; font-weight:500;
  }
  .featured { border-color:var(--coral); background:#fff8f4; }
  @media (max-width:760px) {
    .menu-page { padding:6rem 1.2rem 3rem; }
    .items { grid-template-columns:1fr; }
    .item .photo { width:80px; height:80px; }
  }
</style>
@endpush

@section('body')
<div class="menu-page">
  <h1>{{ $S::setting('menu_title') }}</h1>
  <div class="status {{ $isOpenNow ? 'open' : 'closed' }}">
    {{ $isOpenNow ? $S::setting('hero_badge_line1') : $S::setting('hero_badge_closed_line1') }}
  </div>
  <p class="today">{{ $todaySummary }}</p>

  @forelse($categories as $cat)
    <section class="cat" id="cat-{{ $cat->slug }}">
      <div class="cat-head">
        <span class="icon">{{ $cat->icon ?: '🍽️' }}</span>
        <h2>{{ $cat->name($lang) }}</h2>
      </div>
      @if($cat->description($lang))
        <p class="cat-desc">{{ $cat->description($lang) }}</p>
      @endif

      <div class="items">
        @foreach($cat->activeItems as $it)
          <div class="item {{ $it->is_featured ? 'featured' : '' }}">
            <div class="photo">
              @if($it->imageUrl())
                <img src="{{ $it->imageUrl() }}" alt="{{ $it->name($lang) }}" loading="lazy">
              @else
                <span>{{ $cat->icon ?: '🍽️' }}</span>
              @endif
            </div>
            <div class="body">
              <div class="row">
                <h3>{{ $it->name($lang) }}</h3>
                <span class="price">{{ $it->formattedPrice() }}</span>
              </div>
              @if($it->description($lang))
                <p class="desc">{{ $it->description($lang) }}</p>
              @endif
              @if(!empty($it->tags))
                <div class="tags">
                  @foreach($it->tags as $tag)
                    <span class="tag">{{ $tag }}</span>
                  @endforeach
                </div>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    </section>
  @empty
    <p>—</p>
  @endforelse
</div>
@endsection
