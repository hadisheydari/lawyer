@extends('layouts.main')
@push('imports')
    @vite(['resources/js/methods/numberFormat.js' , 'resources/js/methods/partition-fare.js'  ])
@endpush
@section('title', 'ویرایش پارتیشن ')
@section('content')
    <x-cargo-delivery.partition
    :vehicleDetails="$vehicleDetails"
    :partition="$partition"
    :status="$status"
    :mode="'edit'"
    />

@endsection
@section('scripts')
    <script>

        window.cargoData = {
            fareType: "{{ $cargo->fare_type ?? $partition->cargo->fare_type }}",
            fare: {{ $cargo->fare ?? $partition->cargo->fare}},
            weight: {{ $cargo->weight ?? $partition->cargo->weight}},

            partitionWeightSum: {{ ($cargo->partition_weight ?? $partition->cargo->partition_weight)- $partition->weight?? 0 }},
            maxPartitionWeight: {{ $cargo->weight ?? $partition->cargo->weight ?? 0 }}
        };


    </script>

@endsection
