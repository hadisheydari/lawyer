@extends('layouts.main')
@section('title', ' صاحب کالا ')
@section('header', ' صاحب کالا')

@section('content')
    @if (session('success'))
        <p class="mb-4 text-sm text-green-600 bg-green-100 border border-green-300 rounded p-2">
            {{ session('success') }}
        </p>
    @endif

    <x-table.base-table
        :headers="['نام', 'تلفن', 'نوع صاحب کالا']"
        :columns="['user.name', 'user.phone', 'product_owner_type']"
        :rows="$product_owners"
        :with-index="true"
        :actions="fn($row) => view('components.table.action', [
        'items' => [
            ['name' => 'نمایش', 'route' => route('drivers.show', $row->id), 'bg' => 'text-blue-600', 'icon' => 'lucide-eye'],
            ['name' => 'ویرایش', 'route' => route('drivers.edit', $row->id), 'bg' => 'text-yellow-600', 'icon' => 'lucide-pencil'],
            ['name' => 'حذف', 'route' => route('drivers.destroy', $row->id), 'bg' => 'text-red-600', 'icon' => 'lucide-trash'],
        ]
    ])"
    />



@endsection
