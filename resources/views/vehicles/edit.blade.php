@extends('layouts.main')
@section('title', 'ویرایش  مکانیزم')
@section('content')
    <x-vehicle.vehicle
        :vehicleDetails="$vehicleDetails"
        :mode="'edit'"
        :vehicle="$vehicle"
    />

@endsection
