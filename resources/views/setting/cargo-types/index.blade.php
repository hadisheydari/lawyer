@extends('layouts.main')
@section('title', ' نوع بار')
@section('header', 'نوع بار')

@section('content')
    @if (session('success'))
        <p class="mb-4 text-sm text-green-600 bg-green-100 border border-green-300 rounded p-2">
            {{ session('success') }}
        </p>
    @endif
    <div class="w-full flex justify-end mb-4">
        <x-form.button
            type="button"
            text="بازگشت"
            :action="'window.history.back()'"
        />
    </div>



    <x-table.base-table
        :headers="['نام نوع بار','کد نوع بار ' ]"
        :columns="['name', 'code']"
        :rows="$cargoTypes"
        :with-index="true"
        :actions="fn($row) => view('components.table.action', [
        'items' => [
            ['name' => 'نمایش', 'route' => route('cargo_types.show', $row->id), 'bg' => 'text-blue-600', 'icon' => 'lucide-eye'],
            ['name' => 'ویرایش', 'route' => route('cargo_types.edit', $row->id), 'bg' => 'text-yellow-600', 'icon' => 'lucide-pencil'],
            ['name' => 'حذف', 'route' => route('cargo_types.destroy', $row->id), 'bg' => 'text-red-600', 'icon' => 'lucide-trash'],
        ]
    ])"
    />



@endsection
