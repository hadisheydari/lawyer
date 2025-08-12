@extends('layouts.main')
@push('imports')
    @vite(['resources/js/methods/numberFormat.js' ])
@endpush
@section('title', 'ویرایش پیشنهاد')
@section('content')
    <x-cargo_declaration.cargo-bid
        :cargoBid="$cargo_bid"
        :mode="'edit'"

    />

@endsection
