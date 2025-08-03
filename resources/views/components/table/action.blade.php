<div x-data="{ open: false }" class="relative inline-block text-left">
    <!-- دکمه سه نقطه -->
    <button @click="open = !open" class="p-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="5"
                  d="M12 6v.01M12 12v.01M12 18v.01"/>
        </svg>
    </button>

    <div
        x-show="open"
        x-transition
        @click.outside="open = false"
        x-ref="dropdown"
        x-init="$watch('open', value => {
        if (value) {
            let rect = $el.parentElement.getBoundingClientRect();
            $el.style.position = 'fixed';
            $el.style.top = rect.bottom + 'px';
            $el.style.left = rect.right - $el.offsetWidth + 'px';
        }
    })"
        class="z-50 w-44 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
        style="display: none; min-width: max-content;"
    >
        @foreach($items as $item)
            @if(isset($item['method']) && $item['method'] === 'delete')

                <form action="{{ $item['route'] }}" method="POST">
                    @csrf
                    @method($item['method'] ?? 'DELETE')
                    <button type="button"
                            onclick="confirmDelete(this)"
                            @if(isset($item['icon']))
                                {{--                <x-dynamic-component :component="$item['icon']" class="w-4 h-4" />--}}
                            @endif
                            class="w-full text-right flex items-center px-4 py-2 gap-2 hover:bg-gray-100 {{ $item['bg'] ?? 'text-gray-700' }}">
                        {{ $item['name'] }}
                    </button>
                </form>
            @else
                <a href="{{ $item['route'] }}"
                   class="flex items-center px-4 py-2 gap-2 hover:bg-gray-100 {{ $item['bg'] ?? 'text-gray-700' }}">
                    @if(isset($item['icon']))
                        {{--                <x-dynamic-component :component="$item['icon']" class="w-4 h-4" />--}}
                    @endif
                    {{ $item['name'] }}
                </a>
            @endif
        @endforeach

    </div>
</div>

