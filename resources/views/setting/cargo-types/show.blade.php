@extends('layouts.main')
@section('title', 'نمایش نوع بار ')
@section('content')
    <x-setting.cargo-type
        :mode="'show'"
        :cargoType="$cargo_type"
    />

@endsection
