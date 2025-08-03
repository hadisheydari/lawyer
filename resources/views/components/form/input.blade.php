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
    @elseif($type === 'decimal'  )
        <input
            type="number"
            id="{{$name}}"
            name="{{$name}}"
            step="0.0000001"
            class="w-full px-4 py-2 rounded-lg bg-blue-50 text-blue-900 border border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-700 placeholder:text-blue-500"
            placeholder="{{$placeholder ?? ''}}"
            value="{{ old($name, $value ?? '')}}"
            min="{{$min}}"
            max="{{$max}}"
            {{$required ?? false ? 'required' : ''}}
            {{$readonly ?? false ? 'readonly' : ''}}

        >

    @elseif($type === 'date')
        <input
            type="text"
            id="{{ $name }}_display"
            class="persian-datepicker px-4 py-2 border rounded-lg bg-blue-50 text-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-400"
            placeholder="{{ $placeholder ?? '' }}"
            autocomplete="off"
            value="{{ old($name, isset($value) ? \Morilog\Jalali\Jalalian::forge($value)->format('Y/m/d') : '') }}"
            {{ $disabled ?? false ? 'disabled' : '' }}
            data-pd-target="{{ $name }}"
        >

        <input
            type="hidden"
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ old($name, isset($value) ? \Carbon\Carbon::parse(\Illuminate\Support\Str::of($value)
            ->replace(['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'], ['0','1','2','3','4','5','6','7','8','9'])
        )->format('Y-m-d H:i:s') : '') }}"
        >
    @endif


    @error($name)
    <p class="text-red-500 text-sm mt-1">{{$message}}</p>
    @enderror


</div>
