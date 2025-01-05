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

import JustValidate from 'just-validate';

import { formValidation } from '@form-validation/bundle/popular';
import { SubmitButton } from '@form-validation/plugin-submit-button';
import { Bootstrap5 } from '@form-validation/plugin-bootstrap5';
import { AutoFocus } from '@form-validation/plugin-auto-focus';
import { Trigger } from '@form-validation/plugin-trigger';

window.formValidation = formValidation;
window.SubmitButton = SubmitButton;
window.Bootstrap5 = Bootstrap5;
window.AutoFocus = AutoFocus;
window.Trigger = Trigger;

window.Swiper = Swiper;
window.Swal = Swal;
window.Alpine = Alpine;

Alpine.start();
