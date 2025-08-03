@extends('layouts.main')
@section('title', 'مدیریت رانندگان ')
@section('header', 'مدیریت رانندگان')

@section('content')
    @if (session('success'))
        <p class="mb-4 text-sm text-green-600 bg-green-100 border border-green-300 rounded p-2">
            {{ session('success') }}
        </p>
    @endif

    <x-table.base-table
        :headers="['نام', 'تلفن', 'دارای مکانیزم']"
        :columns="['user.name', 'user.phone', 'vehicle.vehicleDetail.name']"
        :rows="$drivers"
        :with-index="true"
        :actions="fn($row) => view('components.table.action', [
        'items' => [
           $row->vehicle? ['name' => ' حذف راننده ', 'route' => route('vehicles.detachDriver',$row->vehicle?->id), 'bg' => 'text-red-600', 'icon' => 'lucide-trash' , 'method' => 'patch',] :
            ['name' => 'اختصاص مکانیزم', 'route' => route('allocation',  $row->id), 'bg' => 'text-blue-600', 'icon' => 'lucide-eye' ],

        ]
    ])"
    />



@endsection
