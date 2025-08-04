<div class="text-blue-950 font-black text-xl m-12 ">
    @if($locationType === 'origin')
        اطلاعات  بارگیری
    @elseif($locationType === 'destination')
        اطلاعات تخلیه
    @endif
</div>

<div class="grid grid-cols-2 gap-4">


    <div class="">
        <x-form.input
            name="{{$locationType}}[lat]"
            label="طول جغرافیایی"
            type="text"
            placeholder="طول جغرافیایی را وارد کنید"
            value="{{ old('lat' , $cargo->$locationType->lat ?? '') }}"
            :numberFormat="true"
            id="{{$locationType === 'destination' ? 'lat1' : 'lat'}}"

        />

    </div>

    <div class="">
        <x-form.input
            name="{{$locationType}}[lng]"
            label="عرض جغرافیایی"
            type="text"
            placeholder="عرض جغرافیایی را وارد کنید"
            value="{{ old('thickness' , $cargo->$locationType->lng ?? '') }}"
            :numberFormat="true"
            id="{{$locationType === 'destination' ? 'lng1' : 'lng'}}"

        />

    </div>

    <div class="">
        <x-form.select-box
            name="{{$locationType}}[province_id]"
            :options="$provinces ?? []"
            label="استان"
            placeholder="یک گزینه را انتخاب کنید"
            :selected="old('province_id', $cargo->$locationType->province_id ?? '')"
            :multiple="false"
            :required="true"
            :disabled="$isShow"
            id="{{$locationType === 'destination' ? 'province1' : 'province'}}"
        />
    </div>

    <div class="">
        <x-form.select-box
            name="{{$locationType}}[city_id]"
            :options="$cities ??[]"
            label="شهر"
            placeholder="ابتدا استان را انتخاب کنید"
            :selected="old('city_id', $cargo->$locationType->city_id ?? '')"
            :multiple="false"
            :required="true"
            :disabled="$isShow"
            id="{{$locationType === 'destination' ? 'city1' : 'city'}}"
        />
    </div>

    <div class="">
        <x-form.input
            name="{{$locationType}}[description]"
            label="توضیحات"
            type="textarea"
            placeholder="توضیحات را وارد کنید"
            value="{{ old('description' , $cargo->$locationType->description ?? '') }}"
            :readonly="$isShow"
        />

    </div>

    <div class="">
        <x-form.input
            name="{{$locationType}}[address]"
            label="آدرس دقیق"
            type="textarea"
            placeholder="آدرس دقیق را وارد کنید"
            value="{{ old('address' , $cargo->$locationType->address ?? '') }}"
            :readonly="$isShow"
        />

    </div>
    @if($locationType === 'origin')
        <input type="hidden" name="{{$locationType}}[type]" value="{{ $locationType }}">

        <div class="">
            <x-form.input
                name="{{$locationType}}[date_at]"
                label="تاریخ شروع"
                type="date"
                placeholder="تاریخ شروع را وارد کنید"
                value="{{ old('date_at' ,  $cargo->$locationType->date_at  ?? '') }}"
                :readonly="$isShow"

            />

        </div>
    @elseif($locationType === 'destination')
        <input type="hidden" name="{{$locationType}}[type]" value="{{ $locationType }}">

        <div class="">
            <x-form.input
                name="{{$locationType}}[date_at]"
                label="تاریخ پایان"
                type="date"
                placeholder="تاریخ پایان را وارد کنید"
                value="{{ old('date_at' ,  $cargo->$locationType->date_at  ?? '') }}"
                :readonly="$isShow"

            />

        </div>
    @endif


</div>

