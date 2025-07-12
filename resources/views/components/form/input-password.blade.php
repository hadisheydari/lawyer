<div class="m-4 relative" dir="rtl">
    <label for="{{ $name }}" class="block mb-2  font-medium text-blue-900">
        {{ $label ?? ucfirst($name) }}
    </label>
    <div class="relative">
        <input
            type="password"
            id="{{ $name }}"
            name="{{ $name }}"
            minlength="{{$minlength}}"
            required
            class="w-full px-4 py-2 rounded-lg bg-blue-50 text-blue-900 border border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-700 placeholder:text-blue-500 @error($name) border-red-500 @enderror"
            placeholder="{{ $placeholder ?? ucfirst($name) }}"
        >
        <button
            type="button"
            class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-600 cursor-pointer"
            onclick="togglePasswordVisibility('{{ $name }}')"
        >
            <i id="eye-icon-{{ $name }}" class="fa fa-eye"></i>
        </button>
    </div>
    @error($name)
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<script>
    function togglePasswordVisibility(name) {
        const passwordInput = document.getElementById(name);
        const eyeIcon = document.getElementById(`eye-icon-${name}`);

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        }
    }
</script>
