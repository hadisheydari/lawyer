@extends('layouts.main')
@section('title', 'ثبت پارتیشن ')
@section('content')
    <x-cargo-delivery.partition
    :cargo="$cargo"

    />

@endsection
