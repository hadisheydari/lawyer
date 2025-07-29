@extends('layouts.main')
@section('title', 'نمایش راننده ')
@section('content')
    <x-role-info.driver-role
        :provinces="$provinces"
        :cities="$cities"
        :mode="'show'"
        :companies="$companies"
        :driver="$driver"
    />


@endsection
