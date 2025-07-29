@php
    $imageUrl = null;

    if (!empty($currentImage)) {
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($currentImage)) {
            $imageUrl = asset('storage/' . $currentImage);
        }
    }
@endphp

@if($readonly)
    {{-- فقط نمایش تصویر در حالت مشاهده --}}
    <div class="flex flex-col items-center gap-2 border border-blue-300 rounded-lg p-4 bg-blue-50">
        <label class="text-lg font-semibold text-blue-900">{{ $label }}</label>

        @if($imageUrl)
            <img src="{{ $imageUrl }}" alt="تصویر" class="w-64 h-40 object-cover rounded-lg shadow">
        @else
            <span class="text-gray-500 text-sm">تصویری ثبت نشده است.</span>
        @endif
    </div>
@else
    {{-- حالت create یا edit --}}
    <div
        x-data="{
    previewUrl: null,
    oldImage: '{{ $imageUrl }}',
    removeOldImage: false,
    previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            this.previewUrl = URL.createObjectURL(file);
            this.removeOldImage = false;
        }
    },
    removeImage() {
        this.previewUrl = null;
        this.oldImage = null;
        this.removeOldImage = true;
    }
}"

        class="flex flex-col items-center gap-4 p-6 border-2 border-blue-400 bg-blue-50 rounded-xl shadow-md w-full"
    >
        <label class="text-lg font-semibold text-blue-900">{{ $label }}</label>

        {{-- نمایش تصویر قبلی یا جدید --}}
        <template x-if="previewUrl || oldImage">
            <div class="relative w-64 h-40 rounded-lg overflow-hidden border-2 border-blue-300 shadow">
                <img :src="previewUrl ? previewUrl : oldImage" class="object-cover w-full h-full">
                <button type="button"
                        @click="removeImage"
                        class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white px-2 py-1 text-xs rounded shadow">
                    ✕ حذف
                </button>
            </div>
        </template>

        {{-- هیچ تصویری موجود نیست --}}
        <template x-if="!previewUrl && !oldImage">
            <div
                class="flex flex-col items-center justify-center w-64 h-40 border-2 border-dashed border-blue-300 rounded-lg bg-white text-blue-400 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mb-2 text-blue-400" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>تصویری انتخاب نشده</span>
            </div>
        </template>

        <label class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg cursor-pointer shadow text-sm">
            انتخاب تصویر
            <input type="file" name="{{ $name }}" @change="previewImage($event)" accept="image/*"
                   class="hidden" {{ $required ? 'required' : '' }}>
        </label>

        <input type="hidden" name="remove_image" x-model="removeOldImage">
    </div>
@endif
