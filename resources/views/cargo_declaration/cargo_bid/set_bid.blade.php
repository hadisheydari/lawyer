@extends('layouts.main')

@section('title')
    {{ $cargo->date_at ===  null ? 'ثبت' : 'ویرایش'}}  بار آزاد
@endsection
@section('content')
    <div class="text-blue-950 font-black text-2xl m-12 ">
        {{ $cargo->date_at ===  null ? 'ثبت' : 'ویرایش'}} بار آزاد
    </div>
    <x-form.base-form
        :action="route('cargo_bids.set_bid', $cargo->id)"
        :method=" 'PUT' "
        class="space-y-6">
        @csrf
        @if ($errors->any())
            <div class="rounded-md bg-red-50 p-4 text-red-700 text-sm font-medium space-y-1">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li><i class="fa-solid fa-circle-exclamation mr-2"></i>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="grid grid-cols  gap-4">
            <div class="flex justify-center items-center min-h-[300px] p-6">
                <div
                    class="max-w-md w-full bg-blue-100 border border-blue-500 rounded-xl shadow-lg p-6 text-center text-blue-900 font-bold transition-colors hover:bg-blue-200">
                    <input type="hidden" name="redirect_to" value="{{ $cargo->type ?? '' }}">
                    <input type="hidden" name="cargo_id" value="{{ $cargo->id ?? '' }}">
                    <h5 class="mb-4 text-2xl font-extrabold">بار: {{ $cargo->cargoType?->name }}</h5>
                    <p class="text-lg mb-1">مبدا: {{ $cargo->origin?->province?->name }} ,
                        مقصد: {{ $cargo->destination?->province?->name }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">

                <div class="">
                    <x-form.input
                        name="date_at"
                        label="تاریخ شروع"
                        type="date"
                        placeholder="تاریخ شروع را وارد کنید"
                        value="{{old( 'date_at' , $cargo->date_at ?? '') }}"

                    />

                </div>

                <div class="">
                    <x-form.input
                        name="date_to"
                        label="تاریخ پایان"
                        type="date"
                        placeholder="تاریخ شروع را وارد کنید"
                        value="{{old( 'date_to' , $cargo->date_to ?? '') }}"

                    />
                </div>

            </div>


        </div>


        <div>
            <x-form.button
                type="submit"
                text="{{  $cargo->date_at ===  null ? ' ثبت اطلاعات' : 'ویرایش اطلاعات' }}"
                :mode="'edit'"
            />
        </div>
    </x-form.base-form>

@endsection
