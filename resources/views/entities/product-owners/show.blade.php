@extends('layouts.main')
@section('title', 'نمایش کاربر صاحب کالا ')

@section('content')
    @if($product_owner->product_owner_type === 'real')
        <x-role-info.product-owner-real-role
            :provinces="$provinces"
            :cities="$cities"
            :mode="'show'"
            :productOwner="$product_owner"
        />
    @else
        <x-role-info.product-owner-legal-role
            :provinces="$provinces"
            :cities="$cities"
            :mode="'show'"
            :productOwner="$product_owner"
        />

    @endif

@endsection
