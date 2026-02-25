import $ from 'jquery';
window.$ = window.jQuery = $;


import { loadSelect2CDN } from './select2-init.js';
import { loadPersianDatepickerCDN } from './persian-datepicker-loader.js';

document.addEventListener('DOMContentLoaded', async () => {
    try {
        await loadSelect2CDN();
        await loadPersianDatepickerCDN();
    } catch (error) {
        console.error('Error loading resources:', error);
    }
});
import '../css/app.css';
import Alpine from 'alpinejs';


window.Alpine = Alpine;
Alpine.start();

