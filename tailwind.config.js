import forms from '@tailwindcss/forms';

export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./resources/views/components/**/*.blade.php", // اضافه کن
        "./app/View/Components/**/*.php", // اضافه کن
    ],
    theme: {
        extend: {},
    },
    plugins: [
        forms,
    ],
}
