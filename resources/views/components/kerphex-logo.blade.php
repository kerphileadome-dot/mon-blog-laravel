@props(['size' => 'md'])

@php
    $sizes = [
        'sm' => ['mark' => '36px', 'font' => '1rem', 'x' => '0.65rem'],
        'md' => ['mark' => '44px', 'font' => '1.25rem', 'x' => '0.75rem'],
        'lg' => ['mark' => '56px', 'font' => '1.6rem', 'x' => '1rem'],
    ];
    $s = $sizes[$size] ?? $sizes['md'];
@endphp

<div {{ $attributes->merge(['class' => 'kerphex-logo']) }}>
    <div class="kerphex-mark" style="width:{{ $s['mark'] }};height:{{ $s['mark'] }};">
        <span class="kerphex-k">K</span>
        <span class="kerphex-x" style="font-size:{{ $s['x'] }};">X</span>
    </div>
    <div class="kerphex-wordmark">
        <span class="kerphex-name" style="font-size:{{ $s['font'] }};">Kerphe<span class="kerphex-accent">X</span></span>
        <span class="kerphex-tagline">Blog professionnel</span>
    </div>
</div>
