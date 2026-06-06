@extends('layouts.public')

@php $S = \App\Support\Site::class; @endphp

@push('head')
<style>
  .hero {
    min-height:100vh; display:grid; grid-template-columns:1fr 1fr;
    align-items:center; padding:8rem 4rem 4rem; gap:4rem; position:relative; overflow:hidden;
  }
  .hero::before {
    content:''; position:absolute; right:-10%; top:-10%; width:70%; height:120%;
    background:radial-gradient(ellipse at center, var(--coral-light) 0%, transparent 70%);
    opacity:.5; pointer-events:none;
  }
  .hero-text { position:relative; z-index:1; }
  .hero-tag {
    display:inline-block; font-size:.75rem; text-transform:uppercase; letter-spacing:.18em;
    color:var(--coral); font-weight:500; border:1.5px solid var(--coral);
    padding:.35rem .9rem; border-radius:2px; margin-bottom:1.8rem; animation:fadeUp .6s ease both;
  }
  .hero h1 {
    font-family:'Playfair Display',serif; font-size:clamp(3rem,6vw,5.5rem);
    line-height:1.05; font-weight:900; color:var(--black); animation:fadeUp .7s .1s ease both;
  }
  .hero h1 em { font-style:italic; color:var(--coral); }
  .hero-sub { margin-top:1.5rem; font-size:1.1rem; font-weight:300; line-height:1.7; max-width:440px; color:#333; animation:fadeUp .7s .2s ease both; }
  .hero-ctas { display:flex; gap:1rem; margin-top:2.5rem; animation:fadeUp .7s .3s ease both; flex-wrap:wrap; }
  .hero-visual { position:relative; display:flex; justify-content:center; align-items:center; animation:fadeUp .8s .25s ease both; }
  .hero-plate { width:min(420px,90%); aspect-ratio:1; border-radius:50%; background:var(--coral); display:flex; align-items:center; justify-content:center; position:relative; overflow:hidden; }
  .hero-plate::before { content:''; position:absolute; inset:0; background:repeating-conic-gradient(from 0deg, rgba(255,255,255,.06) 0deg 30deg, transparent 30deg 60deg); }
  .hero-plate-inner { font-size:9rem; filter:drop-shadow(0 8px 24px rgba(0,0,0,.15)); animation:spin-slow 20s linear infinite; user-select:none; }
  .hero-badge {
    position:absolute; top:1.5rem; right:-1rem; padding:.9rem 1.1rem; border-radius:2px; text-align:center;
    font-size:.7rem; text-transform:uppercase; letter-spacing:.1em; font-weight:500; line-height:1.5;
  }
  .hero-badge.open   { background:var(--black); color:var(--cream); }
  .hero-badge.closed { background:#6b1f1f; color:#fff; }
  .hero-badge strong { display:block; font-size:1.5rem; font-family:'Playfair Display',serif; font-weight:900; }
  .hero-badge-tiny { font-size:.62rem; opacity:.75; margin-top:.4rem; display:block; }

  #menu-section { background:var(--black); color:var(--cream); }
  #menu-section .section-label { color:var(--coral); }
  #menu-section .section-title { color:var(--cream); }
  #menu-section .section-intro { color:#aaa; font-size:1rem; max-width:500px; margin-bottom:3.5rem; font-weight:300; }
  .menu-grid { display:grid; grid-template-columns:repeat(4, 1fr); gap:1.5rem; max-width:1400px; margin:0 auto; }
  .menu-card {
    background:#111; border:1.5px solid #222; border-radius:4px; padding:1.8rem 1.5rem;
    display:flex; flex-direction:column; align-items:center; text-align:center;
    transition:border-color .25s, transform .2s;
  }
  .menu-card:hover { border-color:var(--coral); transform:translateY(-4px); }
  .menu-card-icon { font-size:2.5rem; margin-bottom:1rem; }
  .menu-card h3 { font-family:'Playfair Display',serif; font-size:1.5rem; font-weight:700; color:var(--coral); margin-bottom:.5rem; }
  .menu-card p { font-size:.85rem; color:#888; font-weight:300; margin-bottom:1.8rem; line-height:1.6; }
  .menu-card-items { width:100%; text-align:left; font-size:.85rem; color:#bbb; }
  .menu-card-items li { list-style:none; padding:.4rem 0; border-bottom:1px solid #1f1f1f; display:flex; justify-content:space-between; gap:.5rem; }
  .menu-card-items li:last-child { border-bottom:none; }
  .menu-card-items .price { color:var(--coral); font-weight:500; flex-shrink:0; }
  .qr-box { background:#fff; padding:1rem; border-radius:4px; display:flex; flex-direction:column; align-items:center; gap:.6rem; margin-top:1.8rem; }
  .qr-box img { display:block; }
  .qr-label { font-size:.65rem; text-transform:uppercase; letter-spacing:.12em; color:#555; font-weight:500; }
  .menu-link {
    display:inline-block; margin-top:1rem; font-size:.78rem; color:var(--coral);
    text-decoration:none; text-transform:uppercase; letter-spacing:.1em; font-weight:500;
    border-bottom:1px solid transparent; transition:border-color .2s;
  }
  .menu-link:hover { border-color:var(--coral); }

  #about { display:grid; grid-template-columns:1fr 1fr; gap:5rem; align-items:center; }
  .about-visual { position:relative; }
  .about-block {
    background:var(--coral); aspect-ratio:3/4; border-radius:4px;
    display:flex; align-items:center; justify-content:center; font-size:7rem;
    position:relative; overflow:hidden;
  }
  .about-block::before { content:''; position:absolute; inset:0; background:repeating-linear-gradient(45deg, transparent, transparent 20px, rgba(255,255,255,.06) 20px, rgba(255,255,255,.06) 21px); }
  .about-float { position:absolute; bottom:-1.5rem; right:-1.5rem; background:var(--black); color:var(--cream); padding:1.5rem 2rem; border-radius:4px; font-family:'Playfair Display',serif; font-size:2.5rem; font-weight:900; line-height:1; }
  .about-float span { display:block; font-family:'DM Sans',sans-serif; font-size:.7rem; font-weight:400; text-transform:uppercase; letter-spacing:.12em; margin-top:.3rem; color:var(--coral); }
  .about-text p { font-size:1rem; font-weight:300; line-height:1.8; color:#333; margin-bottom:1rem; }
  .about-features { display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-top:2rem; }
  .about-feat { display:flex; align-items:flex-start; gap:.6rem; }
  .feat-icon { font-size:1.4rem; flex-shrink:0; }
  .feat-text strong { display:block; font-size:.82rem; text-transform:uppercase; letter-spacing:.06em; font-weight:500; }
  .feat-text span { font-size:.78rem; color:#666; font-weight:300; }

  #social { background:var(--coral); color:var(--cream); text-align:center; }
  #social .section-label { color:rgba(255,255,255,.7); }
  #social .section-title { color:#fff; }
  #social .section-sub { font-size:1rem; font-weight:300; color:rgba(255,255,255,.8); margin-bottom:3rem; }
  .social-grid { display:flex; gap:2rem; justify-content:center; flex-wrap:wrap; }
  .social-card { background:rgba(255,255,255,.12); border:1.5px solid rgba(255,255,255,.25); border-radius:4px; padding:2.5rem 3rem; text-decoration:none; color:#fff; display:flex; flex-direction:column; align-items:center; gap:1rem; min-width:200px; transition:background .25s, transform .2s; }
  .social-card:hover { background:rgba(255,255,255,.25); transform:translateY(-5px); }
  .social-icon { width:56px; height:56px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.6rem; }
  .social-icon.ig { background:linear-gradient(135deg,#f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%); }
  .social-icon.tt { background:#000; }
  .social-card h4 { font-family:'Playfair Display',serif; font-size:1.2rem; font-weight:700; }
  .social-card span { font-size:.8rem; font-weight:300; opacity:.75; }

  #location { background:var(--cream); display:grid; grid-template-columns:1fr 1fr; gap:5rem; align-items:start; }
  .location-detail { display:flex; align-items:flex-start; gap:1rem; margin-bottom:1.5rem; padding-bottom:1.5rem; border-bottom:1px solid var(--coral-light); }
  .location-detail:last-child { border-bottom:none; }
  .loc-icon { width:40px; height:40px; background:var(--coral); border-radius:2px; display:flex; align-items:center; justify-content:center; font-size:1.1rem; flex-shrink:0; color:#fff; }
  .loc-text strong { display:block; font-size:.78rem; text-transform:uppercase; letter-spacing:.1em; font-weight:500; margin-bottom:.2rem; }
  .loc-text p { font-size:.92rem; color:#444; font-weight:300; line-height:1.5; }
  .loc-text a { color:var(--coral); text-decoration:none; font-weight:500; }
  .hours-table { width:100%; font-size:.85rem; color:#444; }
  .hours-table tr.today { background:rgba(255,140,90,.12); font-weight:500; }
  .hours-table td { padding:.35rem .5rem; vertical-align:top; }
  .hours-table td.day { font-weight:500; }
  .map-embed { border-radius:4px; overflow:hidden; border:2px solid var(--coral-light); box-shadow:0 12px 40px rgba(255,140,90,.15); position:sticky; top:5rem; }
  .map-embed iframe { width:100%; height:420px; border:none; display:block; }
  .map-cta { display:block; background:var(--coral); color:#fff; text-align:center; padding:1rem; text-decoration:none; font-size:.85rem; text-transform:uppercase; letter-spacing:.1em; font-weight:500; transition:background .2s; }
  .map-cta:hover { background:var(--coral-dark); }

  @media (max-width:1200px) { .menu-grid { grid-template-columns:repeat(3, 1fr); } }
  @media (max-width:900px) {
    .hero { grid-template-columns:1fr; padding:7rem 1.5rem 3rem; gap:2.5rem; }
    .hero::before { display:none; }
    .menu-grid { grid-template-columns:repeat(2, 1fr); max-width:720px; }
    #about, #location { grid-template-columns:1fr; gap:3rem; }
    .map-embed { position:static; }
  }
  @media (max-width:560px) { .menu-grid { grid-template-columns:1fr; max-width:420px; } }
  @media (max-width:640px) {
    .hero h1 { font-size:2.8rem; }
    .about-features { grid-template-columns:1fr; }
  }
</style>
@endpush

@section('body')
{{-- ──── HERO ──── --}}
<section class="hero" id="home">
  <div class="hero-text">
    <div class="hero-tag">{{ $S::setting('hero_tag') }}</div>
    <h1>{!! $S::setting('hero_h1') !!}</h1>
    <p class="hero-sub">{{ $S::setting('hero_sub') }}</p>
    <div class="hero-ctas">
      <a href="#menu" class="btn-primary">{{ $S::setting('hero_cta1') }}</a>
      <a href="#location" class="btn-outline">{{ $S::setting('hero_cta2') }}</a>
    </div>
  </div>
  <div class="hero-visual">
    <div class="hero-plate"><div class="hero-plate-inner">🌮</div></div>
    <div class="hero-badge {{ $isOpenNow ? 'open' : 'closed' }}">
      @if($isOpenNow)
        <span>{{ $S::setting('hero_badge_line1') }}</span><br>
        <strong>{{ $S::setting('hero_badge_line2') }}</strong>
        <span>{{ $S::setting('hero_badge_line3') }}</span>
      @else
        <span>{{ $S::setting('hero_badge_closed_line1') }}</span><br>
        <strong>{{ $S::setting('hero_badge_line2') }}</strong>
        <span>{{ $S::setting('hero_badge_line3') }}</span>
      @endif
      <span class="hero-badge-tiny">{{ $todaySummary }}</span>
    </div>
  </div>
</section>

{{-- ──── MENU ──── --}}
<section class="block" id="menu-section">
  <a id="menu"></a>
  <p class="section-label">{{ $S::setting('menu_label') }}</p>
  <h2 class="section-title">{{ $S::setting('menu_title') }}</h2>
  <div class="divider"></div>
  <p class="section-intro">{{ $S::setting('menu_intro') }}</p>
  <div class="menu-grid">
    @foreach($categories as $cat)
      <div class="menu-card">
        <div class="menu-card-icon">{{ $cat->icon ?: '🍽️' }}</div>
        <h3>{{ $cat->name($lang) }}</h3>
        <p>{{ $cat->description($lang) }}</p>
        @if($cat->activeItems->count())
          <ul class="menu-card-items">
            @foreach($cat->activeItems->take(5) as $it)
              <li>
                <span>{{ $it->name($lang) }}</span>
                <span class="price">{{ $it->formattedPrice() }}</span>
              </li>
            @endforeach
          </ul>
        @endif
        <div class="qr-box">
          <img src="{{ route('menu.qr') }}?cat={{ $cat->slug }}" width="140" height="140" alt="QR menu">
          <p class="qr-label">{{ $S::setting('menu_qr_label') }}</p>
        </div>
        <a class="menu-link" href="{{ route('menu') }}?lang={{ $lang }}#cat-{{ $cat->slug }}">{{ $S::setting('menu_link_label') }}</a>
      </div>
    @endforeach
  </div>
</section>

{{-- ──── ABOUT ──── --}}
<section class="block" id="about">
  <div class="about-visual">
    <div class="about-block">🇲🇽
      <div style="position:absolute;bottom:1.5rem;left:1.5rem;color:rgba(255,255,255,.18);font-family:'Playfair Display',serif;font-size:6rem;font-weight:900;line-height:1;user-select:none;">RG2</div>
    </div>
    <div class="about-float">Porto<span>📍 Portugal</span></div>
  </div>
  <div class="about-text">
    <p class="section-label">{{ $S::setting('about_label') }}</p>
    <h2 class="section-title">{!! $S::setting('about_title') !!}</h2>
    <div class="divider"></div>
    <p>{{ $S::setting('about_p1') }}</p>
    <p>{{ $S::setting('about_p2') }}</p>
    <div class="about-features">
      <div class="about-feat"><div class="feat-icon">🌽</div><div class="feat-text"><strong>{{ $S::setting('feat1_title') }}</strong><span>{{ $S::setting('feat1_sub') }}</span></div></div>
      <div class="about-feat"><div class="feat-icon">🔥</div><div class="feat-text"><strong>{{ $S::setting('feat2_title') }}</strong><span>{{ $S::setting('feat2_sub') }}</span></div></div>
      <div class="about-feat"><div class="feat-icon">🍹</div><div class="feat-text"><strong>{{ $S::setting('feat3_title') }}</strong><span>{{ $S::setting('feat3_sub') }}</span></div></div>
      <div class="about-feat"><div class="feat-icon">❤️</div><div class="feat-text"><strong>{{ $S::setting('feat4_title') }}</strong><span>{{ $S::setting('feat4_sub') }}</span></div></div>
    </div>
  </div>
</section>

{{-- ──── SOCIAL ──── --}}
<section class="block" id="social">
  <p class="section-label">{{ $S::setting('social_label') }}</p>
  <h2 class="section-title">{{ $S::setting('social_title') }}</h2>
  <div class="divider center"></div>
  <p class="section-sub">{{ $S::setting('social_sub') }}</p>
  <div class="social-grid">
    <a href="{{ $S::setting('instagram_url') }}" target="_blank" class="social-card">
      <div class="social-icon ig">📸</div><h4>Instagram</h4><span>{{ $S::setting('instagram_handle') }}</span>
    </a>
    <a href="{{ $S::setting('tiktok_url') }}" target="_blank" class="social-card">
      <div class="social-icon tt">🎵</div><h4>TikTok</h4><span>{{ $S::setting('tiktok_handle') }}</span>
    </a>
  </div>
</section>

{{-- ──── LOCATION ──── --}}
<section class="block" id="location">
  <div class="location-info">
    <p class="section-label">{{ $S::setting('loc_label') }}</p>
    <h2 class="section-title">{!! $S::setting('loc_title') !!}</h2>
    <div class="divider"></div>
    <div class="location-detail">
      <div class="loc-icon">📍</div>
      <div class="loc-text"><strong>{{ $S::setting('loc_address_title') }}</strong><p>{{ $S::setting('loc_address') }}</p></div>
    </div>
    <div class="location-detail">
      <div class="loc-icon">🕐</div>
      <div class="loc-text">
        <strong>{{ $S::setting('loc_hours_title') }}</strong>
        @php $dayNames = \App\Models\BusinessHour::dayNames($lang); $todayDow = (int) now(config('app.timezone'))->dayOfWeek; @endphp
        <table class="hours-table">
          @for ($d = 1; $d <= 7; $d++)
            @php $dow = $d % 7; $rows = $hoursByDay[$dow] ?? []; @endphp
            <tr class="{{ $dow === $todayDow ? 'today' : '' }}">
              <td class="day">{{ $dayNames[$dow] }}</td>
              <td>
                @if(empty($rows) || collect($rows)->every(fn($r) => $r->is_closed))
                  —
                @else
                  @foreach($rows as $r)
                    @if(!$r->is_closed && $r->open_time && $r->close_time)
                      <div>{{ $r->shiftLabel($lang) }}: {{ $r->formatRange() }}</div>
                    @endif
                  @endforeach
                @endif
              </td>
            </tr>
          @endfor
        </table>
      </div>
    </div>
    <div class="location-detail">
      <div class="loc-icon">📲</div>
      <div class="loc-text">
        <strong>{{ $S::setting('loc_social_title') }}</strong>
        <p>
          <a href="{{ $S::setting('instagram_url') }}" target="_blank">Instagram: {{ $S::setting('instagram_handle') }}</a><br>
          <a href="{{ $S::setting('tiktok_url') }}" target="_blank">TikTok: {{ $S::setting('tiktok_handle') }}</a>
        </p>
      </div>
    </div>
    <div class="location-detail">
      <div class="loc-icon">🗺️</div>
      <div class="loc-text">
        <strong>Google Maps</strong>
        <p><a href="{{ $S::setting('maps_share_url') }}" target="_blank">{{ $S::setting('loc_maps_link') }}</a></p>
      </div>
    </div>
  </div>
  <div class="map-embed">
    <iframe src="{{ $S::setting('maps_embed_src') }}"
            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
            title="{{ $S::setting('brand_name') }}"></iframe>
    <a href="{{ $S::setting('maps_share_url') }}" target="_blank" class="map-cta">{{ $S::setting('loc_maps_cta') }}</a>
  </div>
</section>
@endsection
