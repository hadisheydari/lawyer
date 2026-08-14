@props(['amount', 'unit' => 'تومان', 'label' => null])

<div {{ $attributes->merge(['class' => 'price-box']) }}>
    @if($label)
        <span class="price-box-label">{{ $label }}</span>
    @endif

    <div class="price-box-value">{{ fa_number($amount) }} {{ $unit }}</div>
    <div class="price-box-words">{{ fa_price_words($amount, $unit) }}</div>
</div>
