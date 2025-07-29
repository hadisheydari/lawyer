@extends('layouts.main')
@section('title', 'ویرایش راننده ')
@section('content')
    <x-role-info.driver-role
        :provinces="$provinces"
        :cities="$cities"
        :mode="'edit'"
        :companies="$companies"
        :driver="$driver"
    />


@endsection
