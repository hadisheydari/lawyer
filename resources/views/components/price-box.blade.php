@props(['amount', 'unit' => 'تومان', 'label' => null])

<div {{ $attributes->merge(['class' => 'price-box']) }}>
    @if($label)
        <span class="price-box-label">{{ $label }}</span>
    @endif

    <div class="price-box-value">{{ fa_number($amount) }} {{ $unit }}</div>
    <div class="price-box-words">{{ fa_price_words($amount, $unit) }}</div>
</div>

<style>
    .price-box { display: flex; flex-direction: column; gap: 4px; }
    .price-box-label { font-size: 0.8rem; color: #888; }
    .price-box-value { font-size: 1.1rem; font-weight: 800; color: var(--navy, #102a43); }
    .price-box-words { font-size: 0.75rem; color: #94a3b8; }
</style>