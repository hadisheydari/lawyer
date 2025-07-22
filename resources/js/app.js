import $ from 'jquery';

// حتما اول jQuery رو به window اضافه کن
window.$ = window.jQuery = $;

// بعد select2 رو ایمپورت کن (بدون {} چون default export نیست)
import 'select2';
import 'select2/dist/css/select2.min.css';

import '../css/app.css';
import Alpine from 'alpinejs';
import './chart.js';

document.addEventListener('DOMContentLoaded', () => {
    if ($('.select2').length) {
        $('.select2').select2({
            width: '100%',
            dir: 'rtl',
        });
    }
});

if (!window.Livewire) {
    window.Alpine = Alpine;
    Alpine.start();
}
