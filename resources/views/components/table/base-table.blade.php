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
</div>
