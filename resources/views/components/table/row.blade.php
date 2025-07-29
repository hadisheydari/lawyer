<tr class="bg-blue-300 border-b border-blue-400">
    @if($withIndex)
        <x-table.cell :value="$index + 1" class="bg-blue-200 font-bold" />
    @endif

    @foreach($columns as $col)
        @php
            $cellValue = data_get($row, $col);
        @endphp

        @if($col === 'status')
            <x-table.cell>
                <x-table.status :value="$cellValue" />
            </x-table.cell>
        @else
            <x-table.cell :value="$cellValue ?? '-'" :class="$loop->even ? '' : 'bg-blue-200'" />
        @endif
    @endforeach

    @if(!is_null($actions))
            <x-table.cell >
                {!! $actions($row) !!}
            </x-table.cell>
    @endif
</tr>
