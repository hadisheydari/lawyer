@extends('layouts.main')
@section('title', 'ثبت مکانیزم')
@section('content')
    <x-vehicle.vehicle
        :vehicleDetails="$vehicleDetails"
    />

@endsection
