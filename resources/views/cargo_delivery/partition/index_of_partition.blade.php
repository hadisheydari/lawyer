@extends('layouts.main')

@php
    use App\Enums\Cargo\CargoStatus;

    $text = (!empty($status) && $status !== 'all')
        ? 'لیست تمامی پارتیشن‌های ' . __('cargo_enums.cargo_status.' . $status)
        : 'لیست تمامی پارتیشن ها';
@endphp

@section('title', $text)
@section('header', $text)

@section('content')
    @if (session('success'))
        <p class="mb-4 text-sm text-green-600 bg-green-100 border border-green-300 rounded p-2">
            {{ session('success') }}
        </p>
    @endif

    @if (session('error'))
        <p class="mb-4 text-sm text-red-600 bg-red-100 border border-red-300 rounded p-2">
            {{ session('error') }}
        </p>
    @endif

    @can('write cargo delivery')
        <div class="!m-4 w-full grid justify-start">
            <x-form.button
                name="create"
                type="button"
                text="افزودن پارتیشن"
                :action="route('partitions.create', $cargo)"
            />
        </div>
    @endcan

    <x-table.base-table
        :headers="['نوع بار', 'وزن پارتیشن', 'کرایه', 'وضعیت']"
        :columns="['cargo.cargoType.name', 'weight', 'fare', 'status']"
        :rows="$partitions"
        :with-index="true"
        :actions="fn($row) => view('components.table.action', [
            'items' => array_filter([
                (auth()->user()?->can('write cargo delivery') && $row->status === CargoStatus::FREE)
                    ? ['name' => 'تعیین نوع راننده', 'route' => route('partitions.driver', $row->id), 'bg' => 'text-blue-600', 'icon' => 'driver'] : null,

                (auth()->user()?->can('write cargo delivery') && $row->status === CargoStatus::FREE)
                    ? ['name' => 'ویرایش پارتیشن', 'route' => route('partitions.edit', $row->id), 'bg' => 'text-blue-600', 'icon' => 'pencil'] : null,

                (auth()->user()?->can('write cargo delivery') && $row->status === CargoStatus::FREE)
                    ? ['name' => 'حذف پارتیشن', 'route' => route('partitions.destroy', $row->id), 'bg' => 'text-blue-600', 'icon' => 'trash', 'method' => 'delete'] : null,

                (auth()->user()?->can('write cargo delivery') && $row->status === CargoStatus::RESERVED)
                    ? ['name' => 'ثبت حواله', 'route' => route('partitions.edit', ['partition' => $row->id, 'status' => 'upload']), 'bg' => 'text-blue-600', 'icon' => 'sticky-note'] : null,

                (auth()->user()?->can('write cargo delivery') && $row->status === CargoStatus::RESERVED)
                    ? ['name' => 'ویرایش حواله', 'route' => route('partitions.edit', $row->id), 'bg' => 'text-blue-600', 'icon' => 'sticky-note'] : null,

                (auth()->user()?->can('write cargo delivery') && $row->status === CargoStatus::HAVALE)
                    ? ['name' => 'ثبت بارنامه', 'route' => route('partitions.edit', ['partition' => $row->id, 'status' => 'upload']), 'bg' => 'text-blue-600', 'icon' => 'upload'] : null,

                (auth()->user()?->can('write cargo delivery') && $row->status === CargoStatus::HAVALE)
                    ? ['name' => 'ویرایش بارنامه', 'route' => route('partitions.edit', $row->id), 'bg' => 'text-blue-600', 'icon' => 'upload'] : null,

                ($row->status === CargoStatus::DELIVERED)
                    ? ['name' => 'ثبت امتیاز برای راننده', 'route' => route('partitions.', $row->status), 'bg' => 'text-blue-600', 'icon' => 'star'] : null,

                ['name' => 'نمایش پارتیشن', 'route' => route('partitions.show', $row->id), 'bg' => 'text-yellow-600', 'icon' => 'eye'],
            ])
        ])"
    />
@endsection
