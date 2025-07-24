<div
    x-data="singleImageUploader('{{ $currentImage ?? '' }}')"
    class="flex flex-col items-center gap-4 p-6 border-2 border-blue-400 bg-blue-50 rounded-xl shadow-md w-full ">

    <label class="text-lg font-semibold text-blue-900">{{ $label }}</label>

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

    <template x-if="!previewUrl && !oldImage">
        <div class="flex flex-col items-center justify-center w-64 h-40 border-2 border-dashed border-blue-300 rounded-lg bg-white text-blue-400 text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mb-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>تصویری انتخاب نشده</span>
        </div>
    </template>

    <label class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg cursor-pointer shadow text-sm">
        انتخاب تصویر
        <input type="file" name="{{ $name }}" @change="previewImage($event)" accept="image/*" class="hidden" {{ $required ? 'required' : '' }}>
    </label>

    <input type="hidden" name="remove_image" x-model="removeOldImage">
</div>

<script>
    function singleImageUploader(initialImage) {
        return {
            oldImage: initialImage || '',
            previewUrl: null,
            removeOldImage: false,

            previewImage(event) {
                const file = event.target.files[0];
                if (file) {
                    this.previewUrl = URL.createObjectURL(file);
                    this.removeOldImage = false;
                }
            },
            cancelNewImage() {
                this.previewUrl = null;
                document.querySelector('input[name="{{ $name }}"]').value = '';
            },
            removeImage() {
                this.oldImage = '';
                this.previewUrl = null;
                this.removeOldImage = true;
                document.querySelector('input[name="{{ $name }}"]').value = '';
            }
        };
    }
</script>
