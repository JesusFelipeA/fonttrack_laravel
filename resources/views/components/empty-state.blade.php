@props([
    'icon' => 'bi-inbox',
    'title',
    'text' => null,
])

<div {{ $attributes->merge(['class' => 'ft-empty']) }}>
    <span class="ft-empty__icon"><i class="bi {{ $icon }}" aria-hidden="true"></i></span>
    <p class="ft-empty__title">{{ $title }}</p>
    @if($text)
        <p class="ft-empty__text">{{ $text }}</p>
    @endif
    {{ $slot }}
</div>
