@extends('layouts.main')
@section('title', 'نمایش  نوع بارگیر')
@section('content')
    <x-setting.vehicle-detail
        :mode="'show'"
        :vehicleDetail="$vehicle_detail"
    />

@endsection
