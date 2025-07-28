@extends('layouts.main')
@section('title', ' لیست کاربران شرکت حمل ')
@section('header', ' لیست کاربران شرکت حمل ')

@section('content')
    @php
        $products = [
            ['name' => 'MacBook', 'category' => 'Laptop', 'price' => '$2000', 'status' => 'approved'],
            ['name' => 'Surface', 'category' => 'PC', 'price' => '$1800', 'status' => 'pending'],
            ['name' => 'Mouse', 'category' => 'Accessories', 'price' => '$99', 'status' => 'rejected'],
        ];
    @endphp
    <x-table.base-table
        :headers="['کد ثبت', 'مدیر', 'تلفن', 'وضعیت']"
        :columns="['user.name', 'manager_name', 'manager_phone_number', 'status']"
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



    {{--    route('products.show', $row['id'])--}}
@endsection
