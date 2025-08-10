<div class="flex flex-col m-4">
    <label for="{{ $name }}" class="block mb-2 font-medium text-blue-900 {{ $hidden ?? false ? 'hidden' : '' }}">
        {{ $label ?? ucfirst($name) }}
    </label>

    <select
        id="{{$id}}"
        name="{{ $name }}{{ $multiple ?? false ? '[]' : '' }}"
        class="select2 w-full px-4 py-2 rounded-lg bg-blue-50 text-blue-900 border border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-700 placeholder:text-blue-500 {{ $hidden ?? false ? 'hidden' : '' }}"
        {{ $multiple ?? false ? 'multiple' : '' }}
        {{ $required ?? false ? 'required' : '' }}
        {{ $disabled ?? false ? 'disabled' : '' }}
    >
        @php
            $currentValue = old($name) ?? $selected;
        @endphp

        @if(isset($placeholder))
            <option value="" disabled {{ (!$multiple && empty($currentValue)) ? 'selected' : '' }} hidden>
                {{ $placeholder }}
            </option>
        @endif
        @php
            $currentValue = old($name) ?? $selected;
        @endphp

        @foreach($options as $key => $text)
            <option value="{{ $key }}"
                {{
                    (is_array($currentValue) && in_array($key, $currentValue)) || $key == $currentValue
                    ? 'selected'
                    : ''
                }}>
                {{ $text }}
            </option>
        @endforeach

    </select>



    @error($name)
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
