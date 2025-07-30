@extends('layouts.main')
@section('title', 'ویرایش راننده ')
@section('content')
    <x-role-info.driver-role
        :provinces="$provinces"
        :cities="$cities"
        :mode="'edit'"
        :companies="$companies"
        :driver="$driver"
    >
        @php
            $previous = url()->previous();
            $isSelfRedirect = Str::contains($previous, route('drivers.edit', $driver));
            $redirectTo = $isSelfRedirect ? route('drivers.index') : $previous;
        @endphp

        <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">    </x-role-info.driver-role>

@endsection
