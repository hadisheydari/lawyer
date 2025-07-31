@extends('layouts.main')
@section('title', 'لیست  شرکت های بیمه')
@section('header', 'لیست شرکت های بیمه ')

@section('content')
    @if (session('success'))
        <p class="!mb-4 text-sm text-green-600 bg-green-100 border border-green-300 rounded p-2">
            {{ session('success') }}
        </p>
    @endif
    @can('write setting')
        <div class=" !m-4 w-full grid  justify-start">
            <x-form.button
                name="create"
                type="button"
                text="افزودن بیمه  "
                :action="route('insurances.create')"
            />
        </div>

    @endcan

    <x-table.base-table
        :headers="['نام بیمه','کد  شرکت بیمه ', 'ضریب شرکت بیمه' ]"
        :columns="['name', 'code' , 'coefficient']"
        :rows="$insurances"
        :with-index="true"
        :actions="fn($row) => view('components.table.action',[
        'items' => array_filter([
            ['name' => 'نمایش', 'route' => route('insurances.show', $row->id), 'bg' => 'text-blue-600', 'icon' => 'lucide-eye'],
            auth()->user()?->can('write setting') ? ['name' => 'ویرایش', 'route' => route('insurances.edit', $row->id), 'bg' => 'text-yellow-600', 'icon' => 'lucide-pencil'] : null,
            auth()->user()?->can('write setting') ? ['name' => 'حذف', 'route' => route('insurances.destroy', $row->id), 'bg' => 'text-red-600', 'icon' => 'lucide-trash' ,  'method' => 'delete',] : null,
    ])
    ])"
    />

@endsection
