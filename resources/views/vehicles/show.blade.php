@extends('layouts.main')
@section('title', 'نمایش  مکانیزم')
@section('content')
    <x-vehicle.vehicle
        :vehicleDetails="$vehicleDetails"
        :mode="'show'"
        :vehicle="$vehicle"
    />

@endsection
