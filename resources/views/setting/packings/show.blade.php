@extends('layouts.main')
@section('title', 'نمایش  نوع بسته بندی ')
@section('content')
    <x-setting.packing
        :mode="'show'"
        :packing="$packing"
    />

@endsection
