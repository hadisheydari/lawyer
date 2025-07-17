<div class="flex flex-col m-4">
    <label for="{{ $name }}" class="block mb-2 font-medium text-blue-900 {{ $hidden ?? false ? 'hidden' : '' }}">
        {{ $label ?? ucfirst($name) }}
    </label>

    <select
        id="{{ $name }}"
        name="{{ $name }}{{ $multiple ?? false ? '[]' : '' }}"
        class="select2 w-full px-4 py-2 rounded-lg bg-blue-50 text-blue-900 border border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-700 placeholder:text-blue-500 {{ $hidden ?? false ? 'hidden' : '' }}"
        {{ $multiple ?? false ? 'multiple' : '' }}
        {{ $required ?? false ? 'required' : '' }}
        {{ $disabled ?? false ? 'disabled' : '' }}
    >
        @if(isset($placeholder))
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach($options as $key => $text)
            <option value="{{ $key }}" {{ in_array($key, (array) old($name, $selected ?? [])) ? 'selected' : '' }}>
                {{ $text }}
            </option>
        @endforeach
    </select>

    @error($name)
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
