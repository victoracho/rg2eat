{{-- Un grupo de la carta con sus opciones y subgrupos. Necesita $cat y $lang. --}}
@php $two = $cat->price_2_label || $cat->activeItems->contains(fn ($i) => $i->price_2 !== null); @endphp
<div class="group g-{{ $cat->style ?: 'tab-blue' }} {{ $two ? 'two-prices' : '' }}" id="cat-{{ $cat->slug }}">
  <h3 class="g-title">{{ $cat->name($lang) }}</h3>
  @if($cat->description($lang))
    <p class="g-desc">{{ $cat->description($lang) }}</p>
  @endif
  @if($cat->price_label || $cat->price_2_label)
    <div class="g-labels"><span>{{ $cat->price_label }}</span>@if($two)<span>{{ $cat->price_2_label }}</span>@endif</div>
  @endif
  @if($cat->activeItems->count())
    <ul class="items">
      @foreach($cat->activeItems as $it)
        <li class="it {{ $it->is_featured ? 'featured' : '' }}">
          <span class="name">
            @if($it->imageUrl())<img class="thumb" src="{{ $it->imageUrl() }}" alt="" loading="lazy">@endif
            {{ $it->name($lang) }}
            @foreach($it->tags ?? [] as $tag)
              <span class="tag">{{ $tag === 'new' ? 'NEW!' : strtoupper($tag) }}</span>
            @endforeach
            @if($it->description($lang))<small>{{ $it->description($lang) }}</small>@endif
          </span>
          <span class="pr">{{ $it->formattedPrice() }}</span>
          @if($two)<span class="pr">{{ $it->formattedPrice($it->price_2) }}</span>@endif
        </li>
      @endforeach
    </ul>
  @endif
  @foreach($cat->activeChildren ?? [] as $child)
    @include('public.partials.menu-group', ['cat' => $child])
  @endforeach
</div>
