@extends('layouts.main')

@section('title')
    ویرایش {{ $cargo === 'rfq' ? 'rfq' : 'رزرو' }} بار
@endsection

@section('content')
    <x-cargo_declaration.cargo-reservation
        :cargo="$cargo_reservation"
        :companies="$companies"
        :company="$company"
        :mode="'edit'"
    />

@endsection
