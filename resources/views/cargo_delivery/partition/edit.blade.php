@extends('layouts.main')
@section('content')
    <x-cargo-delivery.partition
    :partiton="$partition"
    :status="$status"
    :mode="'edit'"
    />

@endsection
