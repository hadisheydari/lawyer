@extends('layouts.main')

@section('title')
    ثبت {{ $cargo === 'rfq' ? 'rfq' : 'رزرو' }} بار
@endsection
@section('content')
    <x-cargo_declaration.cargo-reservation
        :cargo="$cargo"
        :companies="$companies"
    />

@endsection
