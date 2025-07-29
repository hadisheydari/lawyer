@extends('layouts.main')
@section('title', 'ویرایش کاربر شرکت حمل ')

@section('content')
    <x-role-info.company-role
        :provinces="$provinces"
        :cities="$cities"
        :mode="'edit'"
        :company="$company"
    />


@endsection
