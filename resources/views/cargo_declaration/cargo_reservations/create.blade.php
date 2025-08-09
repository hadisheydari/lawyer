@extends('layouts.main')

@section('title', 'ثبت بار')
@section('content')
    <x-cargo_declaration.cargo-reservation
        :cargo="$cargo"
        :companies="$companies"
    />

@endsection
