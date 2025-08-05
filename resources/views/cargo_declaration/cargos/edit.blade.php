@extends('layouts.main')
@push('imports')
    @vite(['resources/js/methods/numberFormat.js' , 'resources/js/methods/fareCalculator.js'  ])
@endpush
@section('title', 'ویرایش بار')
@section('content')
    <x-cargo_declaration.cargo
        :provinces="$provinces"
        :cities="$cities"
        :cities1="$cities1"
        :cargoTypes="$cargoTypes"
        :packings="$packings"
        :insurances="$insurances"
        :mode="'edit'"
        :cargo="$cargo"
    />

@endsection

@section('scripts')
    <script id="insurance-data" type="application/json">
        {!! $insurances->mapWithKeys(fn($i) => [$i['id'] => $i['coefficient']])->toJson() !!}
    </script>

@endsection
