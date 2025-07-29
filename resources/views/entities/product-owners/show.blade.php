@extends('layouts.main')
@section('title', 'نمایش کاربر صاحب کالا ')

@section('content')
    @if($productOwner->product_owner_type === 'real')
        <x-role-info.product-owner-real-role
            :provinces="$provinces"
            :cities="$cities"
            :mode="'show'"
            :productOwner="$productOwner"
        />
    @else
        <x-role-info.product-owner-legal-role
            :provinces="$provinces"
            :cities="$cities"
            :mode="'show'"
            :productOwner="$productOwner"
        />

    @endif

@endsection
