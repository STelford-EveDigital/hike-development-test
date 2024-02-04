import './bootstrap';
import { createApp } from 'vue';
import LeadCaptureForm from './components/LeadCaptureForm.vue';

createApp({})
    .component('LeadCaptureForm', LeadCaptureForm)
    .mount('#app')


// import Alpine from 'alpinejs';

// window.Alpine = Alpine;

// Alpine.start();
