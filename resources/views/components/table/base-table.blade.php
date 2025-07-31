@php
    $paginated = $isPaginated();
    $isPaginated = $rows instanceof \Illuminate\Contracts\Pagination\Paginator;
@endphp

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right ">
        <x-table.thead :headers="$headers" :with-index="$withIndex" :with-actions="!is_null($actions)" />
        <tbody>
        @foreach($rows as $index => $row)
            <x-table.row
                :row="$row"
                :columns="$columns"
                :index="$index"
                :with-index="$withIndex"
                :actions="$actions"
            />
        @endforeach
        </tbody>
    </table>

    @if($isPaginated)
        <nav aria-label="Page navigation example" class="mt-4">
            <ul class="flex items-center -space-x-px h-8 text-sm">
                {{-- Previous Page Link --}}
                @if ($rows->onFirstPage())
                    <li>
                    <span class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-blue-400 bg-white border border-e-0 border-blue-300 rounded-s-lg cursor-not-allowed">
                        <span class="sr-only">Previous</span>
                        <svg class="w-2.5 h-2.5 rtl:rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                        </svg>
                    </span>
                    </li>
                @else
                    <li>
                        <a href="{{ $rows->previousPageUrl() }}" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-blue-500 bg-white border border-e-0 border-blue-300 rounded-s-lg hover:bg-blue-100 hover:text-blue-700">
                            <span class="sr-only">Previous</span>
                            <svg class="w-2.5 h-2.5 rtl:rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                            </svg>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($rows->links()->elements[0] as $page => $url)
                    @if ($page == $rows->currentPage())
                        <li>
                            <span aria-current="page" class="z-10 flex items-center justify-center px-3 h-8 leading-tight text-blue-600 border border-blue-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700">{{ $page }}</span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $url }}" class="flex items-center justify-center px-3 h-8 leading-tight text-blue-500 bg-white border border-blue-300 hover:bg-blue-100 hover:text-blue-700">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($rows->hasMorePages())
                    <li>
                        <a href="{{ $rows->nextPageUrl() }}" class="flex items-center justify-center px-3 h-8 leading-tight text-blue-500 bg-white border border-blue-300 rounded-e-lg hover:bg-blue-100 hover:text-blue-700">
                            <span class="sr-only">Next</span>
                            <svg class="w-2.5 h-2.5 rtl:rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                        </a>
                    </li>
                @else
                    <li>
                    <span class="flex items-center justify-center px-3 h-8 leading-tight text-blue-400 bg-white border border-blue-300 rounded-e-lg cursor-not-allowed">
                        <span class="sr-only">Next</span>
                        <svg class="w-2.5 h-2.5 rtl:rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                    </span>
                    </li>
                @endif
            </ul>
        </nav>
    @endif
</div>
