<div class="flex flex-col m-4">
    <label for="{{$name}}" class="block mb-2  font-medium text-blue-900 {{$hidden ?? false ? 'hidden' : ''}}">
        {{$label ?? ucfirst($name)}}
    </label>
    @if($type === 'text' || $type === 'email' )
        <input
            type="{{$type}}"
            id="{{$name}}"
            name="{{$name}}"
            class="w-full px-4 py-2 rounded-lg bg-blue-50 text-blue-900 border border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-700 placeholder:text-blue-500  {{$hidden ?? false ? 'hidden' : ''}}"
            placeholder="{{$placeholder ?? ''}}"
            value="{{ old($name, $value ?? '')}}"
            minlength = "{{$minlength}}"
            maxlength = "{{$maxlength}}"
            {{$required ?? false ? 'required' : ''}}
            {{$readonly ?? false ? 'readonly' : ''}}

        >
    @elseif ($type === 'textarea')
        <textarea
            id="{{ $name }}"
            name="{{ $name }}"
            class="w-full px-4 py-2 rounded-lg bg-blue-50 text-blue-900 border border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-700 placeholder:text-blue-500"
            placeholder="{{ old($placeholder ?? '', $value ?? '') }}"
                     {{ $required ?? false ? 'required' : '' }}
            {{$readonly ?? false ? 'readonly' : ''}}

                >    {{ old($name, $value ?? '') }}
    </textarea>

    @elseif($type === 'number'  )
        <input
            type="{{$type}}"
            id="{{$name}}"
            name="{{$name}}"
            class="w-full px-4 py-2 rounded-lg bg-blue-50 text-blue-900 border border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-700 placeholder:text-blue-500"
            placeholder="{{$placeholder ?? ''}}"
            value="{{ old($name, $value ?? '')}}"
            min="{{$min}}"
            max="{{$max}}"
            {{$required ?? false ? 'required' : ''}}
            {{$readonly ?? false ? 'readonly' : ''}}

        >
    @elseif ($type === 'file')
        <input
            type="file"
            id="{{ $name }}"
            name="{{ $name }}"
            class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 file:bg-blue-500 file:text-white file:px-4 file:py-2 file:rounded-lg file:border-none"
            accept="image/jpeg, image/png, image/jpg"
            {{ $required ?? false ? 'required' : '' }}
        >
    @elseif ($type === 'img')

        <img id="preview-{{ $name }}" src="{{ asset('storage/'. $value ?? '') }}" class="mt-2  w-40 h-40 object-cover rounded-lg border" />

    @elseif($type === 'date')
        <input
            type="text"
            id="{{$name}}"
            name="{{$name}}"
            class="datepicker   px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
            placeholder="{{$placeholder ?? ''}}"
            autocomplete="off"
            value="{{ old($name, $value ?? '')}}"
            {{$disabled ?? false ? 'disabled' : ''}}

        >
    @endif
    @error($name)
    <p class="text-red-500 text-sm mt-1">{{$message}}</p>
    @enderror


</div>
