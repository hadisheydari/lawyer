@extends('layouts.main')
@section('title', 'ویرایش نوع بار ')
@section('content')
    <x-setting.cargo-type
        :mode="'edit'"
        :cargoType="$cargo_type"
    />

@endsection
