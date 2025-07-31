@extends('layouts.main')
@section('title', 'نمایش بیمه ')
@section('content')
    <x-setting.insurance
        :mode="'show'"
        :insurance="$insurance"
    />

@endsection
