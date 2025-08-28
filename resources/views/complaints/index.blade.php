@extends('layouts.main')
@section('title', 'لیست  شکایات')
@section('header', 'لیست شکایات ')

@section('content')
    @if (session('success'))
        <p class="!mb-4 text-sm text-green-600 bg-green-100 border border-green-300 rounded p-2">
            {{ session('success') }}
        </p>
    @endif
    @can('write complaints')
        <div class=" !m-4 w-full grid  justify-start">
            <x-form.button
                name="create"
                type="button"
                text="افزودن شکایت "
                :action="route('complaints.create')"
            />
        </div>

    @endcan

    <x-table.base-table
        :headers="['نام شاکی','عنوان شکایت', 'وضعیت شکایت' ]"
        :columns="['complainant.name', 'title' , 'status']"
        :rows="$complaints"
        :with-index="true"
        :actions="fn($row) => view('components.table.action',[
        'items' => array_filter([
            ['name' => 'نمایش', 'route' => route('complaints.show', $row->id), 'bg' => 'text-blue-600', 'icon' => 'lucide-eye'],
            auth()->user()?->cannot('write complaints') ? ['name' => 'بررسی شکایت', 'route' => route('complaints.edit', $row->id), 'bg' => 'text-yellow-600', 'icon' => 'lucide-pencil'] : null,
            auth()->user()?->can('write complaints') ? ['name' => 'حذف', 'route' => route('complaints.destroy', $row->id), 'bg' => 'text-red-600', 'icon' => 'lucide-trash' ,  'method' => 'delete',] : null,
    ])
    ])"
    />


@endsection
