@extends('layouts.main')
@section('title', ' لیست بارها ')
@section('header', 'لیست بارها ')

@section('content')
    @if (session('success'))
        <p class="mb-4 text-sm text-green-600 bg-green-100 border border-green-300 rounded p-2">
            {{ session('success') }}
        </p>
    @endif
    @if (session('error'))
        <p class="mb-4 text-sm text-red-600 bg-green-100 border border-red-300 rounded p-2">
            {{ session('error') }}
        </p>
    @endif
    @can('write cargo declaration')
        <div class="!m-4 w-full grid justify-start">
            <x-form.button
                    name="create"
                    type="button"
                    text="افزودن بار "
                    :action="route('cargos.create')"
            />
        </div>

    @endcan

    <x-table.base-table
            :headers="[ 'نوع بار' , 'وزن بار' , 'تعداد', 'وضعیت' ,  ]"
            :columns="['cargoType.name' , 'weight' , 'number' , 'type' ]"
            :rows="$cargos"
            :with-index="true"
            :actions="fn($row) => view('components.table.action',[
        'items' => array_filter([
            ['name' => 'نمایش', 'route' => route('cargos.show', $row->id), 'bg' => 'text-blue-600', 'icon' => 'eye'],
            auth()->user()?->can('write cargo declaration') ? ['name' => 'ویرایش', 'route' => route('cargos.edit', $row->id), 'bg' => 'text-yellow-600', 'icon' => 'pencil'] : null,
            $row->type === null && auth()->user()?->can('write cargo declaration')  ? ['name' => 'تعیین نوع بار',  'action' => 'openCargoTypeModal('.$row->id.')', 'bg' => 'text-indigo-600', 'icon' => 'upload']: null,
            auth()->user()?->can('write cargo declaration') ? ['name' => 'حذف', 'route' => route('cargos.destroy', $row->id), 'bg' => 'text-red-600', 'icon' => 'trash' ,  'method' => 'delete',] : null,
    ])
    ])"
    />
    <div id="selectTypeAction" class="fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm z-50 hidden">
        <div class="bg-white w-10/12 md:w-8/12 lg:w-6/12 mx-auto mt-24 p-6 rounded-lg shadow-lg relative">
            <button id="modalCloseBtn" class="absolute top-4 right-4 text-2xl text-gray-700 ">
                <i class="fa fa-close"></i>
            </button>
            <div class="text-blue-800 font-black text-2xl m-12 ">
                لطفا وضعیت بار را تایین کنید
            </div>
            <div class="">
                <x-form.button
                               action="goToCargoType('free')"
                               text="آزاد" type="button">
                    <i class="fa fa-legal text-2xl"></i>
                </x-form.button>
            </div>

            <div class="">
                <x-form.button
                               action="goToCargoType('rfq')"
                               text="rfq" type="button">
                    <i class="fa fa-id-badge text-2xl"></i>
                </x-form.button>
            </div>

            <div class="mb-4">
                <x-form.button
                               action="goToCargoType('reserve')"
                               text="رزرو" type="button">
                    <i class="fa fa-check-square text-2xl"></i>
                </x-form.button>
            </div>


        </div>
    </div>


@endsection
@section('scripts')
    <script>
        let selectedCargoId = null;

        function openCargoTypeModal(id) {
            selectedCargoId = id;
            document.getElementById('selectTypeAction').classList.remove('hidden');
        }

        function goToCargoType(type) {
            if (!selectedCargoId) return;

            const url = `/cargos/${selectedCargoId}/type/${type}`;
            window.location.href = url;
        }

        document.getElementById('modalCloseBtn').addEventListener('click', function () {
            document.getElementById('selectTypeAction').classList.add('hidden');
        });
    </script>
@endsection
