@extends('layouts.main')
@push('imports')
    @vite(['resources/js/methods/numberFormat.js' ])
@endpush
@section('title', 'ثبت پیشنهاد')
@section('content')

    <x-cargo_declaration.cargo-bid
        :cargo="$cargo"
    />

@endsection
