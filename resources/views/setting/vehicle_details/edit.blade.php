@extends('layouts.main')
@section('title', 'ویرایش  نوع بارگیر')
@section('content')
    <x-setting.vehicle-detail
        :mode="'edit'"
        :vehicleDetail="$vehicle_detail"
    />

@endsection
