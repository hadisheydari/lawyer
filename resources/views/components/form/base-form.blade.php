<div>

    <form action="{{$action}}" method="POST"  enctype="multipart/form-data" class="space-y-4 p-10 rounded bg-white shadow-lg">
        @csrf
        @if ($method === 'PUT' || $method === 'PATCH')
            @method($method)
        @endif
        <div class="flex flex-col">
            {{ $slot }}

        </div>

    </form>
</div>
