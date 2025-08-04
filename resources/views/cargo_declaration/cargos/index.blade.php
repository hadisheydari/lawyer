@extends('layouts.main')
@section('title', ' لیست بارها ')
@section('header', 'لیست بارها ')

@section('content')
    @if (session('success'))
        <p class="mb-4 text-sm text-green-600 bg-green-100 border border-green-300 rounded p-2">
            {{ session('success') }}
        </p>
    @endif
    @can('write cargo declaration')
        <div class="!m-4 w-full grid justify-start">
            <x-form.button
                name="create"
                type="button"
                text="افزودن بار "
                :action="route('cargos.create')"
            />
        </div>

    @endcan

    <x-table.base-table
        :headers="[ 'نوع بار' , 'وزن بار' , 'تعداد']"
        :columns="['cargoType.name' , 'weight' , 'number' ]"
        :rows="$cargos"
        :with-index="true"
        :actions="fn($row) => view('components.table.action',[
        'items' => array_filter([
            ['name' => 'نمایش', 'route' => route('cargos.show', $row->id), 'bg' => 'text-blue-600', 'icon' => 'lucide-eye'],
            auth()->user()?->can('write cargo declaration') ? ['name' => 'ویرایش', 'route' => route('cargos.edit', $row->id), 'bg' => 'text-yellow-600', 'icon' => 'lucide-pencil'] : null,
            auth()->user()?->can('write cargo declaration') ? ['name' => 'حذف', 'route' => route('cargos.destroy', $row->id), 'bg' => 'text-red-600', 'icon' => 'lucide-trash' ,  'method' => 'delete',] : null,
    ])
    ])"
    />

@endsection
