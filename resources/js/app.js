import '../css/app.css';
import Alpine from 'alpinejs';
import './chart.js'

if (!window.Livewire) {
    window.Alpine = Alpine;
    Alpine.start();
}
