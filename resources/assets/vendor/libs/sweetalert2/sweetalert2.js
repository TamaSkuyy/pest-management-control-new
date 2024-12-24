// import * as SwalPlugin from 'sweetalert2/dist/sweetalert2';
import Swal from 'sweetalert2/dist/sweetalert2';

Swal.mixin({
  buttonsStyling: false,
  customClass: {
    confirmButton: 'btn btn-primary',
    cancelButton: 'btn btn-label-danger',
    denyButton: 'btn btn-label-secondary'
  }
});

export { Swal };
window.Swal = Swal;
