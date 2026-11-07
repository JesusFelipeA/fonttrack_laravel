@props([
    'title',
    'eyebrow' => null,
    'icon' => null,
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'ft-page-header']) }}>
    <div>
        @if($eyebrow)
            <div class="ft-page-header__eyebrow">
                @if($icon)<i class="bi {{ $icon }}" aria-hidden="true"></i>@endif
                {{ $eyebrow }}
            </div>
        @endif
        <h1 class="ft-page-header__title">{{ $title }}</h1>
        @if($description)
            <p class="ft-page-header__description">{{ $description }}</p>
        @endif
    </div>

    @isset($actions)
        <div class="ft-page-header__actions">{{ $actions }}</div>
    @endisset
</div>
