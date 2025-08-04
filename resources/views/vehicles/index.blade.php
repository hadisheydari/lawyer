@extends('layouts.main')
@section('title', ' لیست مکانیزم ها')
@section('header', 'لیست مکانیزم ها')

@section('content')
    @if (session('success'))
        <p class="mb-4 text-sm text-green-600 bg-green-100 border border-green-300 rounded p-2">
            {{ session('success') }}
        </p>
    @endif
    @can('write vehicles')
        <div class="!m-4 w-full grid justify-start">
            <x-form.button
                name="create"
                type="button"
                text="افزودن مکانیزم "
                :action="route('vehicles.create')"
            />
        </div>

    @endcan

    <x-table.base-table
        :headers="[ 'نوع مکانیزم' , 'مرکز هزینه' , 'قسمت اول پلاک', 'حرف پلاک' , 'قسمت دوم پلاک'  , 'قسمت سوم پلاک(کد استان)' ]"
        :columns="['vehicleDetail.name' , 'cost_center' , 'plate_first' , 'plate_letter' ,  'plate_second' , 'plate_third']"
        :rows="$vehicles"
        :with-index="true"
        :actions="fn($row) => view('components.table.action',[
        'items' => array_filter([
            ['name' => 'نمایش', 'route' => route('vehicles.show', $row->id), 'bg' => 'text-blue-600', 'icon' => 'lucide-eye'],
            auth()->user()?->can('write vehicles') ? ['name' => 'ویرایش', 'route' => route('vehicles.edit', $row->id), 'bg' => 'text-yellow-600', 'icon' => 'lucide-pencil'] : null,
            auth()->user()?->can('write vehicles') ? ['name' => 'حذف', 'route' => route('vehicles.destroy', $row->id), 'bg' => 'text-red-600', 'icon' => 'lucide-trash' ,  'method' => 'delete',] : null,
    ])
    ])"
    />

@endsection
