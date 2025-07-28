<thead class="text-x uppercase bg-blue-100 border-b border-blue-800">
<tr>
    @if($withIndex)
        <th class="px-6 py-3 bg-blue-300">#</th>
    @endif

    @foreach($headers as $header)
        <th class="px-6 py-3 bg-blue-300">{{ $header }}</th>
    @endforeach

    @if($withActions)
        <th class="px-6 py-3 bg-blue-300">عملیات</th>
    @endif
</tr>
</thead>

