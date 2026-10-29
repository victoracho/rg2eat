{{-- Carta por grupos, estilo carta impresa. Necesita $categories (con activeItems) y $lang. --}}
@php
  $sectionNames = [
    'food'   => ['es' => 'la carta', 'en' => 'the menu', 'pt' => 'a carta'],
    'drinks' => ['es' => 'bebidas',  'en' => 'drinks',   'pt' => 'bebidas'],
  ];
  $sections = $categories->groupBy(fn ($c) => $c->section ?: 'food');
@endphp

@once
@push('head')
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Kaushan+Script&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
<style>
  :root { --orange:#F05A28; --aqua:#2EC4D6; }
  .script { font-family:'Kaushan Script',cursive; font-weight:400; color:var(--red); text-shadow:3px 3px 0 var(--yellow); line-height:1.1; }
  .section { margin-top:3.5rem; scroll-margin-top:6rem; }
  .section > h2 { font-size:clamp(2.4rem,5vw,3.6rem); text-align:center; margin-bottom:2.5rem; }
  .section > h2::before, .section > h2::after { content:'• • • • •'; font-size:1rem; color:var(--red); text-shadow:none; letter-spacing:.3em; vertical-align:middle; margin:0 1rem; }
  .groups { columns:3 340px; column-gap:3rem; }

  /* GRUPO */
  .group { break-inside:avoid; margin-bottom:2.6rem; position:relative; }
  .g-title { display:inline-block; font-family:'Bebas Neue',sans-serif; font-weight:400; font-size:1.9rem; letter-spacing:.02em; line-height:1; padding:.35rem .9rem .2rem; margin-bottom:.6rem; transform:rotate(-1.5deg); }
  .g-desc { font-size:.74rem; color:#555; margin:-.2rem 0 .7rem; }
  .g-labels { display:flex; justify-content:flex-end; gap:0; font-family:'Kaushan Script',cursive; color:var(--red); font-size:1.05rem; }
  .g-labels span { width:4.2rem; text-align:right; }

  .g-tab-blue > .g-title { background:var(--blue);   color:#fff; }
  .g-tab-yellow > .g-title { background:var(--yellow); color:var(--red); }
  .g-tab-red > .g-title { background:var(--orange); color:#fff; }
  .g-brush-yellow > .g-title, .g-brush-aqua > .g-title {
    border-radius:45% 55% 40% 60% / 60% 35% 65% 40%; padding:.4rem 1.4rem .25rem;
  }
  .g-brush-yellow > .g-title { background:var(--yellow); color:var(--red); }
  .g-brush-aqua > .g-title { background:var(--aqua); font-family:'Kaushan Script',cursive; color:var(--red); text-shadow:2px 2px 0 #fff; font-size:1.8rem; }
  .g-box-black { border:2.5px solid var(--black); padding:1.6rem 1.2rem 1rem; margin-top:1.4rem; }
  .g-box-black > .g-title { position:absolute; top:-1.15rem; left:1rem; background:var(--black); color:#fff; transform:rotate(-2deg); }
  .g-dotted-red, .g-dotted-black { border:3px dotted var(--red); padding:2.9rem 1.2rem 1rem; margin-top:2rem; }
  .g-dotted-black { border-color:var(--black); }
  .g-dotted-red > .g-title, .g-dotted-black > .g-title {
    position:absolute; top:-1.6rem; left:50%; transform:translateX(-50%) rotate(-2deg); white-space:nowrap;
    background:var(--cream); padding:0 .6rem; font-family:'Kaushan Script',cursive; font-size:2.3rem; text-shadow:3px 3px 0 var(--yellow);
  }
  .g-dotted-red > .g-title { color:var(--red); }
  .g-dotted-black > .g-title { color:var(--blue); }
  .g-dotted-red > .g-desc, .g-dotted-black > .g-desc { text-align:center; font-family:'Bebas Neue',sans-serif; font-size:1rem; letter-spacing:.08em; color:var(--black); }

  /* SUBGRUPO dentro de un recuadro */
  .group .group { margin:1.3rem 0 0; }
  .g-title-red > .g-title { color:var(--red); padding:0; transform:none; font-size:2rem; }
  .g-fill-orange, .g-fill-blue { padding:.9rem 1rem .6rem; color:#fff; }
  .g-fill-orange { background:var(--orange); }
  .g-fill-blue   { background:var(--blue); }
  .g-fill-orange > .g-title, .g-fill-blue > .g-title { display:none; }
  .g-fill-orange .it .name, .g-fill-blue .it .name { font-family:'Bebas Neue',sans-serif; font-size:1.45rem; font-weight:400; text-transform:uppercase; padding-left:0; }
  .g-fill-orange .it .name::before, .g-fill-blue .it .name::before { display:none; }
  .g-fill-orange .it .name small, .g-fill-blue .it .name small { display:block; color:rgba(255,255,255,.85); font-family:'Poppins',sans-serif; font-size:.66rem; margin:-.15rem 0 0; }
  .g-fill-orange .it .pr, .g-fill-blue .it .pr { color:var(--yellow); font-size:1.6rem; }

  /* OPCIÓN */
  .items { list-style:none; }
  .it { display:grid; grid-template-columns:1fr 4.2rem; align-items:baseline; padding:.28rem 0; }
  .two-prices .it { grid-template-columns:1fr 4.2rem 4.2rem; }
  .it .name { font-size:.88rem; font-weight:500; text-transform:uppercase; letter-spacing:.02em; padding-left:1.1rem; position:relative; }
  .it .name::before { content:'◆'; color:var(--red); position:absolute; left:0; top:.1em; font-size:.7rem; }
  .it .name small { text-transform:none; letter-spacing:0; font-weight:400; font-size:.7rem; color:#777; margin-left:.35rem; }
  .it .thumb { width:34px; height:34px; object-fit:cover; border-radius:50%; vertical-align:middle; margin-right:.4rem; }
  .it .tag { display:inline-block; background:var(--yellow); color:var(--black); font-size:.55rem; font-weight:500; padding:.05rem .3rem; margin-left:.3rem; transform:rotate(-3deg); vertical-align:middle; }
  .it .pr { font-family:'Bebas Neue',sans-serif; font-size:1.35rem; text-align:right; line-height:1; }
  .it.featured .name { font-family:'Bebas Neue',sans-serif; font-size:1.35rem; color:var(--blue); text-transform:none; font-weight:400; }
  .it.featured .name small { display:block; font-family:'Poppins',sans-serif; margin:0; font-size:.72rem; }
  .it.featured .pr { background:var(--yellow); padding:.15rem .3rem; justify-self:end; }

  @media (max-width:760px) {
    .section > h2::before, .section > h2::after { display:none; }
  }
</style>
@endpush
@endonce

@forelse($sections as $key => $groups)
  <section class="section" id="{{ $key }}">
    <h2 class="script">{{ $sectionNames[$key][$lang] ?? $key }}</h2>
    <div class="groups">
      @foreach($groups as $cat)
        @include('public.partials.menu-group')
      @endforeach
    </div>
  </section>
@empty
<p>—</p>
@endforelse
