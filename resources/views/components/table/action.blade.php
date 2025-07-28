<div x-data="{ open: false }" class="relative text-left">
    <button @click="open = !open" class="p-1 ">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="5"
                  d="M12 6v.01M12 12v.01M12 18v.01"/>
        </svg>
    </button>

    <div x-show="open" @click.away="open = false"
         class="absolute z-10 mt-2 w-40 bg-white rounded shadow-lg ring-1 ring-black ring-opacity-5 text-sm">
        @foreach($items as $item)
            <a href="{{ $item['route'] }}"
               class="flex items-center px-4 py-2 gap-2 hover:bg-gray-100 {{ $item['bg'] ?? 'text-gray-700' }}">
                @if(isset($item['icon']))
{{--                    <x-dynamic-component :component="$item['icon']" class="w-4 h-4" />--}}
                @endif
                {{ $item['name'] }}
            </a>
        @endforeach
    </div>
</div>
