@extends('layouts.main')
@php
    $text = ( !empty($status) && $status !== 'all') ?  ' لیست تمامی پارتیشن‌های ' .  __('cargo_enums.cargo_status.' . $status): 'لیست تمامی پارتیشن ها ';
@endphp
@section('title', 'لیست بار های تخصیص داده شده' )
@section('header', 'لیست بار های تخصیص داده شده' )

@section('content')
    @if (session('success'))
        <p class="mb-4 text-sm text-green-600 bg-green-100 border border-green-300 rounded p-2">
            {{ session('success') }}
        </p>
    @endif
    @if (session('error'))
        <p class="mb-4 text-sm text-red-600 bg-green-100 border border-red-300 rounded p-2">
            {{ session('error') }}
        </p>
    @endif


    <x-table.base-table
        :headers="[ 'نوع بار' , 'وزن حمل شده' , 'کرایه', 'وضعیت'   ]"
        :columns="['cargoType.name' , 'partition_weight' , 'fare' , 'type' ]"
        :rows="$cargos"
        :with-index="true"
        :actions="fn($row) => view('components.table.action',[
        'items' => array_filter([
            ['name' =>  ' نمایش ', 'route' => route('partitions.show', $row->id), 'bg' => 'text-blue-600', 'icon' => 'eye'],
            !isset($status) ? ['name' => 'لیست تمام پارتیشن ها', 'route' => route('partitions.index_of_partition', ['cargo' => $row->id, 'status' => 'all']), 'bg' => 'text-blue-600', 'icon' => 'navicon']
            : ['name' => $text , 'route' => route('partitions.index_of_partition', ['cargo' => $row->id, 'status' => $status]), 'bg' => 'text-blue-600', 'icon' => 'navicon'],
            auth()->user()?->can('write cargo delivery') ? ['name' => 'ثبت پارتیشن', 'route' => route('partitions.create', $row->id), 'bg' => 'text-yellow-600', 'icon' => 'pencil'] : null,
    ])
    ])"
    />


@endsection
