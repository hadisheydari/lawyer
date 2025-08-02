@extends('layouts.main')
@section('title', ' راننده ')
@section('header', ' راننده')

@section('content')
    @if (session('success'))
        <p class="mb-4 text-sm text-green-600 bg-green-100 border border-green-300 rounded p-2">
            {{ session('success') }}
        </p>
    @endif

    <x-table.base-table
        :headers="['نام', 'تلفن', 'دارای مکانیزم']"
        :columns="['user.name', 'user.phone', 'vehicleDetail.name']"
        :rows="$drivers"
        :with-index="true"
        :actions="fn($row) => view('components.table.action', [
        'items' => [
           $row->vehicleDetail? ['name' => 'اختصاص', 'route' => route('drivers.show', $row->id), 'bg' => 'text-blue-600', 'icon' => 'lucide-eye'] : null,
            ['name' => 'ویرایش', 'route' => route('drivers.edit', $row->id), 'bg' => 'text-yellow-600', 'icon' => 'lucide-pencil'],
            ['name' => 'حذف', 'route' => route('drivers.destroy', $row->id), 'bg' => 'text-red-600', 'icon' => 'lucide-trash'],
        ]
    ])"
    />



@endsection
