@props(['name', 'label' => null, 'values' => [], 'placeholder' => 'تایپ کنید و Enter بزنید...', 'help' => null])

<div class="form-group">
    @if($label)
        <label class="form-label">{{ $label }}</label>
    @endif
    <div class="tag-input-wrap" data-name="{{ $name }}" data-initial='@json(array_values($values ?? []))'>
        <div class="tag-chips"></div>
        <div class="tag-hidden-inputs"></div>
        <input type="text" class="tag-input-field" placeholder="{{ $placeholder }}">
    </div>
    @if($help)
        <small style="color:#94a3b8;font-size:0.78rem;display:block;margin-top:6px;">{{ $help }}</small>
    @endif
</div>