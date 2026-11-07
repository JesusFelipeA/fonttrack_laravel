@props([
    'label',
    'value',
    'icon' => 'bi-bar-chart',
    'meta' => null,
    'href' => null,
])

<div {{ $attributes->merge(['class' => 'ft-stat']) }}>
    <div class="ft-stat__label">
        <span class="ft-stat__icon"><i class="bi {{ $icon }}" aria-hidden="true"></i></span>
        @if($href)
            <a href="{{ $href }}" class="stretched-link text-reset text-decoration-none">{{ $label }}</a>
        @else
            {{ $label }}
        @endif
    </div>
    <div class="ft-stat__value">{{ $value }}</div>
    @if($meta)
        <div class="ft-stat__meta">{{ $meta }}</div>
    @endif
    {{ $slot }}
</div>
