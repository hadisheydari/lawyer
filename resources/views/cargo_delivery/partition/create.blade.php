@extends('layouts.main')
@push('imports')
    @vite(['resources/js/methods/numberFormat.js' , 'resources/js/methods/partition-fare.js'  ])
@endpush
@section('title', 'ثبت پارتیشن ')
@section('content')
    <x-cargo-delivery.partition
    :cargo="$cargo"
    :partition="$partition"
    :vehicleDetails="$vehicleDetails"
    />

@endsection

@section('scripts')
<script>

    window.cargoData = {
        fareType: "{{ $cargo->fare_type }}",
        fare: {{ $cargo->fare }},
        weight: {{ $cargo->weight }},
        partitionWeightSum: {{ $cargo->partition_weight ?? 0 }},
        maxPartitionWeight: {{ $cargo->weight }}
    };

</script>

@endsection
