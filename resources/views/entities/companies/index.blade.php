@extends('layouts.main')
@section('title', ' لیست کاربران شرکت حمل ')
@section('header', ' لیست کاربران شرکت حمل ')

@section('content')
    @if (session('success'))
        <p class="mb-4 text-sm text-green-600 bg-green-100 border border-green-300 rounded p-2">
            {{ session('success') }}
        </p>
    @endif

    <x-table.base-table
        :headers="['نام', 'تلفن', 'نوع شرکت']"
        :columns="['user.name', 'user.phone', 'company_type']"
        :rows="$companies"
        :with-index="true"
        :actions="fn($row) => view('components.table.action', [
        'items' => [
            ['name' => 'نمایش', 'route' => route('companies.show', $row->id), 'bg' => 'text-blue-600', 'icon' => 'lucide-eye'],
            ['name' => 'ویرایش', 'route' => route('companies.edit', $row->id), 'bg' => 'text-yellow-600', 'icon' => 'lucide-pencil'],
            ['name' => 'حذف', 'route' => route('companies.destroy', $row->id), 'bg' => 'text-red-600', 'icon' => 'lucide-trash'],
        ]
    ])"
    />
    <div class="text-right text-xl font-semibold text-blue-950 m-5">
       لیست راننده ها
    </div>
    <x-table.base-table
        :headers="['نام', 'تلفن', 'نوع راننده']"
        :columns="['user.name', 'user.phone', 'property']"
        :rows="$drivers"
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
