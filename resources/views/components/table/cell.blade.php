@php
    $paths = [
        "labels.$value",
        "statuses.$value",
        "role_types.$value",
        "roles.$value",
    ];

    $translatedValue = collect($paths)
        ->first(fn($path) => Lang::has($path))
        ? __(collect($paths)->first(fn($path) => Lang::has($path)))
        : $value;
@endphp

<td class="px-6 py-4 {{ $class }}">
    {{ $slot->isEmpty() ? $translatedValue : $slot }}
</td>
