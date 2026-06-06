<!DOCTYPE html>
<html lang="{{ $lang ?? 'es' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', \App\Support\Site::setting('page_title'))</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
  :root {
    --coral: #FF8C5A; --cream: #FEF4EC; --black: #000;
    --coral-dark: #E66B3A; --coral-light: #FFB89A; --coral-pale: #FFF0E8;
  }
  * { margin:0; padding:0; box-sizing:border-box; }
  html { scroll-behavior:smooth; }
  body { font-family:'DM Sans',sans-serif; background:var(--cream); color:var(--black); overflow-x:hidden; }
  a { color:inherit; }

  /* NAV */
  nav.site-nav {
    position:fixed; top:0; left:0; right:0; z-index:100;
    display:flex; align-items:center; justify-content:space-between;
    padding:1.2rem 4rem; background:rgba(254,244,236,.92); backdrop-filter:blur(12px);
    border-bottom:1.5px solid var(--coral-light); transition:box-shadow .3s;
  }
  nav.site-nav.scrolled { box-shadow:0 4px 30px rgba(255,140,90,.18); }
  .nav-logo { display:flex; align-items:center; text-decoration:none; }
  .nav-logo img { height:42px; width:auto; display:block; }
  .nav-logo span.fallback { font-family:'Playfair Display',serif; font-weight:900; font-size:1.4rem; color:var(--black); }
  .nav-links { display:flex; gap:2rem; list-style:none; align-items:center; }
  .nav-links a {
    text-decoration:none; color:var(--black); font-size:.9rem; font-weight:500;
    letter-spacing:.04em; text-transform:uppercase; position:relative; transition:color .2s;
  }
  .nav-links a::after {
    content:''; position:absolute; left:0; bottom:-3px; width:0; height:2px;
    background:var(--coral); transition:width .25s;
  }
  .nav-links a:hover { color:var(--coral); }
  .nav-links a:hover::after { width:100%; }
  .nav-reserve {
    background:var(--coral); color:#fff !important; padding:.55rem 1.4rem;
    border-radius:2px; transition:background .2s !important;
  }
  .nav-reserve:hover { background:var(--coral-dark) !important; }
  .nav-reserve::after { display:none !important; }

  .lang-switcher {
    display:flex; align-items:center; gap:.3rem;
    background:rgba(255,140,90,.1); border:1.5px solid var(--coral-light);
    border-radius:3px; padding:.2rem .3rem;
  }
  .lang-btn {
    background:none; border:none; cursor:pointer;
    font-size:.75rem; font-weight:600; font-family:'DM Sans',sans-serif;
    color:#888; padding:.28rem .48rem; border-radius:2px;
    text-transform:uppercase; letter-spacing:.06em; transition:background .15s,color .15s; line-height:1; text-decoration:none;
  }
  .lang-btn:hover { color:var(--coral); }
  .lang-btn.active { background:var(--coral); color:#fff; }
  .lang-sep { color:var(--coral-light); font-size:.7rem; user-select:none; }

  section.block { padding:6rem 4rem; }
  .section-label { font-size:.72rem; text-transform:uppercase; letter-spacing:.2em; color:var(--coral); font-weight:500; margin-bottom:.8rem; }
  .section-title { font-family:'Playfair Display',serif; font-size:clamp(2rem,4vw,3.2rem); font-weight:900; line-height:1.1; margin-bottom:1rem; }
  .divider { width:60px; height:3px; background:var(--coral); margin:1.2rem 0 2rem; }
  .divider.center { margin:1.2rem auto 2rem; }
  .btn-primary {
    background:var(--coral); color:#fff; padding:.85rem 2rem; border:none; cursor:pointer;
    font-family:'DM Sans',sans-serif; font-size:.9rem; font-weight:500;
    text-transform:uppercase; letter-spacing:.08em; text-decoration:none; display:inline-block;
    border-radius:2px; transition:background .2s,transform .15s;
  }
  .btn-primary:hover { background:var(--coral-dark); transform:translateY(-2px); }
  .btn-outline {
    background:transparent; color:var(--black); padding:.85rem 2rem;
    border:1.5px solid var(--black); cursor:pointer; font-family:'DM Sans',sans-serif;
    font-size:.9rem; font-weight:500; text-transform:uppercase; letter-spacing:.08em;
    text-decoration:none; display:inline-block; border-radius:2px;
    transition:background .2s,color .2s,transform .15s;
  }
  .btn-outline:hover { background:var(--black); color:var(--cream); transform:translateY(-2px); }
  footer.site-footer { background:var(--black); color:#555; text-align:center; padding:2.5rem 4rem; font-size:.8rem; font-weight:300; }
  footer.site-footer a { color:var(--coral); text-decoration:none; }
  footer.site-footer strong { color:var(--cream); }

  @media (max-width: 900px) {
    nav.site-nav { padding:1rem 1.5rem; }
    .nav-links { gap:1rem; }
    section.block { padding:4rem 1.5rem; }
  }
  @media (max-width: 640px) {
    .nav-links li.hide-mobile { display:none; }
  }
  @keyframes fadeUp { from { opacity:0; transform:translateY(28px);} to { opacity:1; transform:translateY(0);} }
  @keyframes spin-slow { from { transform:rotate(0);} to { transform:rotate(360deg);} }
</style>
@stack('head')
</head>
<body>

@php
  $brandLogo = \App\Support\Site::setting('brand_logo_url');
  $brandName = \App\Support\Site::setting('brand_name');
  $supportedLangs = ['es','en','pt'];
@endphp

<nav class="site-nav" id="navbar">
  <a href="{{ route('home') }}?lang={{ $lang }}" class="nav-logo">
    @if($brandLogo)
      <img src="{{ $brandLogo }}" alt="{{ $brandName }}">
    @else
      <span class="fallback">{{ $brandName ?: 'RG2 Eat' }}</span>
    @endif
  </a>
  <ul class="nav-links">
    <li class="hide-mobile"><a href="{{ route('home') }}?lang={{ $lang }}#menu">{{ \App\Support\Site::setting('nav_menu') }}</a></li>
    <li class="hide-mobile"><a href="{{ route('home') }}?lang={{ $lang }}#about">{{ \App\Support\Site::setting('nav_about') }}</a></li>
    <li class="hide-mobile"><a href="{{ route('home') }}?lang={{ $lang }}#social">{{ \App\Support\Site::setting('nav_social') }}</a></li>
    <li class="hide-mobile"><a href="{{ route('home') }}?lang={{ $lang }}#location">{{ \App\Support\Site::setting('nav_location') }}</a></li>
    <li><a href="{{ \App\Support\Site::setting('instagram_url') }}" target="_blank" class="nav-reserve">{{ \App\Support\Site::setting('nav_reserve') }}</a></li>
    <li>
      <div class="lang-switcher">
        @foreach ($supportedLangs as $i => $code)
          @if ($i > 0) <span class="lang-sep">|</span> @endif
          <a class="lang-btn {{ $lang === $code ? 'active' : '' }}"
             href="{{ url()->current() }}?lang={{ $code }}">{{ strtoupper($code) }}</a>
        @endforeach
      </div>
    </li>
  </ul>
</nav>

@yield('body')

<footer class="site-footer">
  <p><strong>{{ \App\Support\Site::setting('brand_name') }}</strong> — <span>{{ \App\Support\Site::setting('footer_tagline') }}</span> · {{ \App\Support\Site::setting('loc_address') }}</p>
  <p style="margin-top:.5rem;">
    <a href="{{ \App\Support\Site::setting('instagram_url') }}" target="_blank">Instagram</a> ·
    <a href="{{ \App\Support\Site::setting('tiktok_url') }}" target="_blank">TikTok</a> ·
    <a href="{{ \App\Support\Site::setting('maps_share_url') }}" target="_blank">{{ \App\Support\Site::setting('footer_map') }}</a> ·
    <a href="{{ route('login') }}" style="opacity:.6;">Admin</a>
  </p>
  <p style="margin-top:.8rem;font-size:.72rem;">{{ \App\Support\Site::setting('footer_copy') }}</p>
</footer>

<script>
  const navbar = document.getElementById('navbar');
  if (navbar) {
    window.addEventListener('scroll', () => navbar.classList.toggle('scrolled', window.scrollY > 40));
  }
</script>
@stack('scripts')
</body>
</html>
