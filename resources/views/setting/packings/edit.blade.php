@extends('layouts.main')
@section('title', 'ویرایش نوع بسته بندی ')
@section('content')
    <x-setting.packing
        :mode="'edit'"
        :packing="$packing"
    />

@endsection
