import './bootstrap';

import Alpine from 'alpinejs';

/*
  Add custom scripts here
*/
import.meta.glob([
  '../assets/img/**',
  // '../assets/json/**',
  '../assets/vendor/fonts/**'
]);

import Swiper from 'swiper/bundle';
import Swal from 'sweetalert2';

window.Swiper = Swiper;
window.Swal = Swal;
window.Alpine = Alpine;

Alpine.start();
