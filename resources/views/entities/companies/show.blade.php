@extends('layouts.main')
@section('title', 'نمایش کاربر شرکت حمل ')

@section('content')
    <x-role-info.company-role
        :provinces="$provinces"
        :cities="$cities"
        :mode="'show'"
        :company="$company"
    />


@endsection
