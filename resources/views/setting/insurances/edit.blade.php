@extends('layouts.main')
@section('title', 'ویرایش بیمه ')
@section('content')
    <x-setting.insurance
        :mode="'edit'"
        :insurance="$insurance"
    />

@endsection
