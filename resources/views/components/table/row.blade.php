<tr class="bg-blue-300 border-b border-blue-400">
    @if($withIndex)
        <x-table.cell :value="$index + 1" class="bg-blue-200 font-bold " />
    @endif

    @foreach($columns as $col)

        @if($col === 'status')
            <x-table.cell>
                <x-table.status :value="data_get($row, $column)" />
            </x-table.cell>
        @else
            <x-table.cell :value="data_get($row, $column) ?? '-'" :class="$loop->even ? '' : 'bg-blue-200'" />
        @endif
    @endforeach

    @if(!is_null($actions))
        <x-table.cell class="bg-blue-200">
            {!! $actions($row) !!}
        </x-table.cell>
    @endif
</tr>



