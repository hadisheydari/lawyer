@extends('layouts.main')
@push('imports')
    @vite(['resources/js/methods/numberFormat.js' , 'resources/js/methods/fareCalculatre.js'  , 'resources/js/methods/cargoCity.js' ])
@endpush
@section('title', 'ثبت بار')
@section('content')

    <x-cargo_declaration.cargo
        :provinces="$provinces"
        :cargoTypes="$cargoTypes"
        :packings="$packings"
        :insurances="$insurances"

    />

@endsection

@section('scripts')
    <script id="insurance-data" type="application/json">
        {!! $insurances->mapWithKeys(fn($i) => [$i['id'] => $i['coefficient']])->toJson() !!}
    </script>

@endsection
