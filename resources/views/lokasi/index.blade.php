@extends('layouts/contentNavbarLayout')

@section('title', 'Lokasi')

@section('vendor-style')
    @vite('resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss')
    @vite('resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss')
    @vite('resources/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.scss')
    @vite('resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss')
    <!-- Row Group CSS -->
    @vite('resources/assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.scss')

    {{-- @vite('resources/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') --}}
    @vite('resources/assets/vendor/libs/sweetalert2/sweetalert2.scss')
@endsection

@section('vendor-script')
    @vite('resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')
    {{-- @vite('resources/assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')
    @vite('resources/assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')
    @vite('resources/assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') --}}
    @vite('resources/assets/vendor/libs/sweetalert2/sweetalert2.js')
@endsection

@section('page-script')
    <script type="text/javascript">
        'use strict';
        // import { route } from 'ziggy-js';

        // datatable (jquery)
        document.addEventListener('DOMContentLoaded', function() {
            // datatable (jquery)
            $(function() {
                var dt_basic_table = $('#datatables-basic'),
                    dt_basic;

                // DataTable with buttons
                // --------------------------------------------------------------------

                if (dt_basic_table.length) {
                    dt_basic = dt_basic_table.DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ route('master.lokasi.data') }}",
                            type: "GET",
                        },
                        columns: [{
                                data: 'id'
                            },
                            {
                                data: 'lokasi_kode'
                            },
                            {
                                data: 'lokasi_nama'
                            },
                            {
                                data: 'lokasi_jenis'
                            },
                            {
                                data: 'lokasi_lapisan_pengaman'
                            },
                            {
                                data: ''
                            }
                        ],
                        columnDefs: [{
                                // For Responsive
                                className: 'control',
                                orderable: false,
                                searchable: false,
                                responsivePriority: 2,
                                targets: 0,
                                render: function(data, type, full, meta) {
                                    return '';
                                }
                            },
                            // {
                            //     // For Checkboxes
                            //     targets: 1,
                            //     orderable: false,
                            //     searchable: false,
                            //     responsivePriority: 3,
                            //     checkboxes: true,
                            //     render: function() {
                            //         return '<input type="checkbox" class="dt-checkboxes form-check-input">';
                            //     },
                            //     checkboxes: {
                            //         selectAllRender: '<input type="checkbox" class="form-check-input">'
                            //     }
                            // },
                            // {
                            //     targets: 2,
                            //     searchable: false,
                            //     visible: false
                            // },
                            // {
                            //     // Avatar image/badge, Name and post
                            //     targets: 3,
                            //     responsivePriority: 4,
                            //     render: function(data, type, full, meta) {
                            //         var $user_img = full['avatar'],
                            //             $name = full['full_name'],
                            //             $post = full['post'];
                            //         if ($user_img) {
                            //             // For Avatar image
                            //             var $output =
                            //                 '<img src="' + assetsPath + 'img/avatars/' + $user_img +
                            //                 '" alt="Avatar" class="rounded-circle">';
                            //         } else {
                            //             // For Avatar badge
                            //             var stateNum = Math.floor(Math.random() * 6);
                            //             var states = ['success', 'danger', 'warning', 'info', 'dark',
                            //                 'primary', 'secondary'
                            //             ];
                            //             var $state = states[stateNum],
                            //                 $name = full['full_name'],
                            //                 $initials = $name.match(/\b\w/g) || [];
                            //             $initials = (($initials.shift() || '') + ($initials.pop() ||
                            //                 '')).toUpperCase();
                            //             $output =
                            //                 '<span class="avatar-initial rounded-circle bg-label-' +
                            //                 $state + '">' + $initials + '</span>';
                            //         }
                            //         // Creates full output for row
                            //         var $row_output =
                            //             '<div class="d-flex justify-content-start align-items-center user-name">' +
                            //             '<div class="avatar-wrapper">' +
                            //             '<div class="avatar me-2">' +
                            //             $output +
                            //             '</div>' +
                            //             '</div>' +
                            //             '<div class="d-flex flex-column">' +
                            //             '<span class="emp_name text-truncate">' +
                            //             $name +
                            //             '</span>' +
                            //             '<small class="emp_post text-truncate text-muted">' +
                            //             $post +
                            //             '</small>' +
                            //             '</div>' +
                            //             '</div>';
                            //         return $row_output;
                            //     }
                            // },
                            // {
                            //     responsivePriority: 1,
                            //     targets: 4
                            // },
                            {
                                // Label
                                targets: -2,
                                render: function(data, type, full, meta) {
                                    var $status_number = full['status'];
                                    var $status = {
                                        1: {
                                            title: 'Current',
                                            class: 'bg-label-primary'
                                        },
                                        2: {
                                            title: 'Professional',
                                            class: ' bg-label-success'
                                        },
                                        3: {
                                            title: 'Rejected',
                                            class: ' bg-label-danger'
                                        },
                                        4: {
                                            title: 'Resigned',
                                            class: ' bg-label-warning'
                                        },
                                        5: {
                                            title: 'Applied',
                                            class: ' bg-label-info'
                                        }
                                    };
                                    if (typeof $status[$status_number] === 'undefined') {
                                        return data;
                                    }
                                    return (
                                        '<span class="badge ' + $status[$status_number]
                                        .class +
                                        '">' + $status[$status_number].title + '</span>'
                                    );
                                }
                            },
                            {
                                // Actions
                                targets: -1,
                                title: '{{ __('Aksi') }}',
                                orderable: false,
                                searchable: false,
                                render: function(data, type, full, meta) {
                                    return (
                                        '<a href="#" class="edit-record btn btn-sm btn-icon item-edit"><i class="bx bxs-edit"></i></a>' +
                                        '<a href="#" class="delete-record btn btn-sm btn-icon item-trash"><i class="bx bxs-trash"></i></a>'
                                    );
                                }
                            }
                        ],
                        order: [
                            [1, 'desc']
                        ],
                        dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                        displayLength: 10,
                        lengthMenu: [10, 25, 50, 75, 100],
                        buttons: [
                            // {
                            //     extend: 'collection',
                            //     className: 'btn btn-label-primary dropdown-toggle me-2',
                            //     text: '<i class="bx bx-export me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                            //     buttons: [{
                            //             extend: 'print',
                            //             text: '<i class="bx bx-printer me-1" ></i>Print',
                            //             className: 'dropdown-item',
                            //             exportOptions: {
                            //                 columns: [3, 4, 5, 6, 7],
                            //                 // prevent avatar to be display
                            //                 format: {
                            //                     body: function(inner, coldex, rowdex) {
                            //                         if (inner.length <= 0) return inner;
                            //                         var el = $.parseHTML(inner);
                            //                         var result = '';
                            //                         $.each(el, function(index, item) {
                            //                             if (item.classList !== undefined &&
                            //                                 item.classList.contains(
                            //                                     'user-name')) {
                            //                                 result = result + item.lastChild
                            //                                     .firstChild.textContent;
                            //                             } else if (item.innerText ===
                            //                                 undefined) {
                            //                                 result = result + item
                            //                                     .textContent;
                            //                             } else result = result + item
                            //                                 .innerText;
                            //                         });
                            //                         return result;
                            //                     }
                            //                 }
                            //             },
                            //             customize: function(win) {
                            //                 //customize print view for dark
                            //                 $(win.document.body)
                            //                     .css('color', config.colors.headingColor)
                            //                     .css('border-color', config.colors.borderColor)
                            //                     .css('background-color', config.colors.bodyBg);
                            //                 $(win.document.body)
                            //                     .find('table')
                            //                     .addClass('compact')
                            //                     .css('color', 'inherit')
                            //                     .css('border-color', 'inherit')
                            //                     .css('background-color', 'inherit');
                            //             }
                            //         },
                            //         {
                            //             extend: 'csv',
                            //             text: '<i class="bx bx-file me-1" ></i>Csv',
                            //             className: 'dropdown-item',
                            //             exportOptions: {
                            //                 columns: [3, 4, 5, 6, 7],
                            //                 // prevent avatar to be display
                            //                 format: {
                            //                     body: function(inner, coldex, rowdex) {
                            //                         if (inner.length <= 0) return inner;
                            //                         var el = $.parseHTML(inner);
                            //                         var result = '';
                            //                         $.each(el, function(index, item) {
                            //                             if (item.classList !== undefined &&
                            //                                 item.classList.contains(
                            //                                     'user-name')) {
                            //                                 result = result + item.lastChild
                            //                                     .firstChild.textContent;
                            //                             } else if (item.innerText ===
                            //                                 undefined) {
                            //                                 result = result + item
                            //                                     .textContent;
                            //                             } else result = result + item
                            //                                 .innerText;
                            //                         });
                            //                         return result;
                            //                     }
                            //                 }
                            //             }
                            //         },
                            //         {
                            //             extend: 'excel',
                            //             text: '<i class="bx bxs-file-export me-1"></i>Excel',
                            //             className: 'dropdown-item',
                            //             exportOptions: {
                            //                 columns: [3, 4, 5, 6, 7],
                            //                 // prevent avatar to be display
                            //                 format: {
                            //                     body: function(inner, coldex, rowdex) {
                            //                         if (inner.length <= 0) return inner;
                            //                         var el = $.parseHTML(inner);
                            //                         var result = '';
                            //                         $.each(el, function(index, item) {
                            //                             if (item.classList !== undefined &&
                            //                                 item.classList.contains(
                            //                                     'user-name')) {
                            //                                 result = result + item.lastChild
                            //                                     .firstChild.textContent;
                            //                             } else if (item.innerText ===
                            //                                 undefined) {
                            //                                 result = result + item
                            //                                     .textContent;
                            //                             } else result = result + item
                            //                                 .innerText;
                            //                         });
                            //                         return result;
                            //                     }
                            //                 }
                            //             }
                            //         },
                            //         {
                            //             extend: 'pdf',
                            //             text: '<i class="bx bxs-file-pdf me-1"></i>Pdf',
                            //             className: 'dropdown-item',
                            //             exportOptions: {
                            //                 columns: [3, 4, 5, 6, 7],
                            //                 // prevent avatar to be display
                            //                 format: {
                            //                     body: function(inner, coldex, rowdex) {
                            //                         if (inner.length <= 0) return inner;
                            //                         var el = $.parseHTML(inner);
                            //                         var result = '';
                            //                         $.each(el, function(index, item) {
                            //                             if (item.classList !== undefined &&
                            //                                 item.classList.contains(
                            //                                     'user-name')) {
                            //                                 result = result + item.lastChild
                            //                                     .firstChild.textContent;
                            //                             } else if (item.innerText ===
                            //                                 undefined) {
                            //                                 result = result + item
                            //                                     .textContent;
                            //                             } else result = result + item
                            //                                 .innerText;
                            //                         });
                            //                         return result;
                            //                     }
                            //                 }
                            //             }
                            //         },
                            //         {
                            //             extend: 'copy',
                            //             text: '<i class="bx bx-copy me-1" ></i>Copy',
                            //             className: 'dropdown-item',
                            //             exportOptions: {
                            //                 columns: [3, 4, 5, 6, 7],
                            //                 // prevent avatar to be display
                            //                 format: {
                            //                     body: function(inner, coldex, rowdex) {
                            //                         if (inner.length <= 0) return inner;
                            //                         var el = $.parseHTML(inner);
                            //                         var result = '';
                            //                         $.each(el, function(index, item) {
                            //                             if (item.classList !== undefined &&
                            //                                 item.classList.contains(
                            //                                     'user-name')) {
                            //                                 result = result + item.lastChild
                            //                                     .firstChild.textContent;
                            //                             } else if (item.innerText ===
                            //                                 undefined) {
                            //                                 result = result + item
                            //                                     .textContent;
                            //                             } else result = result + item
                            //                                 .innerText;
                            //                         });
                            //                         return result;
                            //                     }
                            //                 }
                            //             }
                            //         }
                            //     ]
                            // },
                            {
                                text: '<i class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">{{ __('Tambah Data') }}</span>',
                                className: 'create-new btn btn-primary'
                            }
                        ],
                        responsive: {
                            details: {
                                display: $.fn.dataTable.Responsive.display.modal({
                                    header: function(row) {
                                        var data = row.data();
                                        return 'Details of ' + data['full_name'];
                                    }
                                }),
                                type: 'column',
                                renderer: function(api, rowIdx, columns) {
                                    var data = $.map(columns, function(col, i) {
                                        return col.title !==
                                            '' // ? Do not show row in modal popup if title is blank (for check box)
                                            ?
                                            '<tr data-dt-row="' +
                                            col.rowIndex +
                                            '" data-dt-column="' +
                                            col.columnIndex +
                                            '">' +
                                            '<td>' +
                                            col.title +
                                            ':' +
                                            '</td> ' +
                                            '<td>' +
                                            col.data +
                                            '</td>' +
                                            '</tr>' :
                                            '';
                                    }).join('');

                                    return data ? $('<table class="table"/><tbody />').append(
                                        data) : false;
                                }
                            }
                        }
                    });
                    $('div.head-label').html('<h5 class="card-title mb-0">{{ __('Data Lokasi') }}</h5>');
                }

                // Add New record
                // On form submit, if form is valid
                fv.on('core.form.valid', function() {
                    var new_lokasi_kode = $('.add-new-record .dt-kode-lokasi').val(),
                        new_lokasi_nama = $('.add-new-record .dt-nama-lokasi').val(),
                        new_lokasi_jenis = $('.add-new-record .dt-jenis-lokasi').val(),
                        new_lokasi_lapisan_pengaman = $('.add-new-record .dt-lapisan-pengaman').val();

                    if (new_lokasi_kode != '') {
                        $.ajax({
                            url: '{{ route('master.lokasi.store') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                lokasi_kode: new_lokasi_kode,
                                lokasi_nama: new_lokasi_nama,
                                lokasi_jenis: new_lokasi_jenis,
                                lokasi_lapisan_pengaman: new_lokasi_lapisan_pengaman,
                            },
                            success: function(response) {
                                // console.log(response); // Check the response in the console
                                if (response.status == 'success') {
                                    // Handle success
                                    dt_basic.ajax.reload();
                                    $('.add-new-record .dt-kode-lokasi').val('');
                                    $('.add-new-record .dt-nama-lokasi').val('');
                                    $('.add-new-record .dt-jenis').val('');
                                    $('.add-new-record .dt-lapisan-pengaman').val('');
                                    offCanvasEl.hide();
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: response.message,
                                    });
                                } else {
                                    // Handle error
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.message,
                                    });
                                }
                            },
                            error: function(xhr) {
                                // Handle server errors
                                console.log(xhr.responseText); // Log the error response
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'An error occurred while processing your request.',
                                });
                            }
                        });
                    }
                });

                // // Delete Record
                // $('.datatables-basic tbody').on('click', '.delete-record', function () {
                //     dt_basic.row($(this).parents('tr')).remove().draw();
                // });
            });

            let fv, offCanvasEl, fvedit, offEditCanvasEl;
            // document.addEventListener('DOMContentLoaded', function(e) {
            (function() {
                const formAddNewRecord = document.getElementById('form-add-new-record');

                setTimeout(() => {
                    const newRecord = document.querySelector('.create-new'),
                        offCanvasElement = document.querySelector('#add-new-record');

                    // To open offCanvas, to add new record
                    if (newRecord) {
                        newRecord.addEventListener('click', () => {
                            offCanvasEl = new bootstrap.Offcanvas(offCanvasElement);
                            // Empty fields and select into default value on offCanvas open
                            offCanvasElement.querySelectorAll('input').forEach(el => el.value =
                                '');
                            offCanvasEl.show();
                        });
                    }
                }, 200);

                // Form validation for Add new record
                fv = formValidation(formAddNewRecord, {
                    fields: {
                        lokasi_kode: {
                            validators: {
                                notEmpty: {
                                    message: '{{ __('Kode Lokasi perlu diisi') }}'
                                }
                            }
                        },
                        lokasi_nama: {
                            validators: {
                                notEmpty: {
                                    message: '{{ __('Nama Lokasi perlu diisi') }}'
                                }
                            }
                        },
                        lokasi_jenis: {
                            validators: {
                                notEmpty: {
                                    message: '{{ __('Jenis Lokasi perlu dipilih') }}'
                                }
                            }
                        },
                    },
                    plugins: {
                        trigger: new Trigger(),
                        bootstrap5: new Bootstrap5({
                            eleValidClass: '',
                            rowSelector: '.col-sm-12'
                        }),
                        submitButton: new SubmitButton(),
                        autoFocus: new AutoFocus()
                    }
                });

                // // FlatPickr Initialization & Validation
                // flatpickr(formAddNewRecord.querySelector('[name="basicDate"]'), {
                //     enableTime: false,
                //     // See https://flatpickr.js.org/formatting/
                //     dateFormat: 'm/d/Y',
                //     // After selecting a date, we need to revalidate the field
                //     onChange: function() {
                //         fv.revalidateField('basicDate');
                //     }
                // });
            })();
            // });

            $('#datatables-basic').on('draw.dt', function() {
                // edit record
                const formUpdateRecord = document.getElementById('form-update-record');

                setTimeout(() => {
                    const editRecord = document.querySelectorAll('.edit-record'),
                        offCanvasUpdateElement = document.querySelector('#update-record');
                    // To open offCanvas, to update new record
                    if (editRecord) {
                        editRecord.forEach(el => {
                            el.addEventListener('click', function() {
                                offEditCanvasEl = new bootstrap.Offcanvas(
                                    offCanvasUpdateElement);
                                // Empty fields and select into default value on offCanvas open
                                //get data from row
                                var data = $('#datatables-basic').DataTable().row($(
                                    this).parents('tr')).data();
                                offCanvasUpdateElement.querySelectorAll('input')
                                    .forEach(el => el.value = '');
                                offCanvasUpdateElement.querySelector(
                                    '.dt-id-edit-lokasi').value = data.id;
                                offCanvasUpdateElement.querySelector(
                                    '.dt-kode-lokasi').value = data.lokasi_kode;
                                offCanvasUpdateElement.querySelector(
                                    '.dt-nama-lokasi').value = data.lokasi_nama;
                                if (data.lokasi_jenis == 'Indoor') {
                                    offCanvasUpdateElement.querySelector(
                                        '.dt-jenis-lokasi').value = 1;
                                } else {
                                    offCanvasUpdateElement.querySelector(
                                        '.dt-jenis-lokasi').value = 2;
                                }
                                offCanvasUpdateElement.querySelector(
                                    '.dt-lapisan-pengaman').value = data.lokasi_lapisan_pengaman;
                                offEditCanvasEl.show();
                            });
                        });
                    }
                }, 300);

                // Form validation for Add new record
                fvedit = formValidation(formUpdateRecord, {
                    fields: {
                        fields: {
                            lokasi_kode: {
                                validators: {
                                    notEmpty: {
                                        message: '{{ __('Kode Lokasi perlu diisi') }}'
                                    }
                                }
                            },
                            lokasi_nama: {
                                validators: {
                                    notEmpty: {
                                        message: '{{ __('Nama Lokasi perlu diisi') }}'
                                    }
                                }
                            },
                            lokasi_jenis: {
                                validators: {
                                    notEmpty: {
                                        message: '{{ __('Jenis Lokasi perlu dipilih') }}'
                                    }
                                }
                            },
                        },
                    },
                    plugins: {
                        trigger: new Trigger(),
                        bootstrap5: new Bootstrap5({
                            eleValidClass: '',
                            rowSelector: '.col-sm-12'
                        }),
                        submitButton: new SubmitButton(),
                        autoFocus: new AutoFocus()
                    }
                });

                // // FlatPickr Initialization & Validation
                // flatpickr(formAddNewRecord.querySelector('[name="basicDate"]'), {
                //     enableTime: false,
                //     // See https://flatpickr.js.org/formatting/
                //     dateFormat: 'm/d/Y',
                //     // After selecting a date, we need to revalidate the field
                //     onChange: function() {
                //         fv.revalidateField('basicDate');
                //     }
                // });

                // Edit record
                fvedit.on('core.form.valid', function() {
                    var id = $('.update-record .dt-id-edit-lokasi').val(),
                        lokasi_kode = $('.update-record .dt-kode-lokasi').val(),
                        lokasi_nama = $('.update-record .dt-nama-lokasi').val(),
                        lokasi_jenis = $('.update-record .dt-jenis-lokasi').val(),
                        lokasi_lapisan_pengaman = $('.update-record .dt-lapisan-pengaman').val();

                    if (lokasi_kode != '') {
                        $.ajax({
                            url: '{{ route('master.lokasi.update', ['id' => ':id']) }}'
                                .replace(
                                    ':id', id),
                            type: 'PATCH',
                            data: {
                                _token: '{{ csrf_token() }}',
                                lokasi_kode: lokasi_kode,
                                lokasi_nama: lokasi_nama,
                                lokasi_jenis: lokasi_jenis,
                                lokasi_lapisan_pengaman: lokasi_lapisan_pengaman,
                            },
                            success: function(response) {
                                // console.log(response); // Check the response in the console
                                if (response.status == 'success') {
                                    // Handle success
                                    $('#datatables-basic').DataTable().ajax.reload();
                                    $('.update-record .dt-id-edit-lokasi').val('');
                                    $('.update-record .dt-kode-lokasi').val('');
                                    $('.update-record .dt-nama-lokasi').val('');
                                    $('.update-record .dt-jenis-lokasi').val('');
                                    $('.update-record .dt-lapisan-pengaman').val('');
                                    offEditCanvasEl.hide();
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: response.message,
                                    });
                                } else {
                                    // Handle error
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.message,
                                    });
                                }
                            },
                            error: function(xhr) {
                                // Handle server errors
                                console.log(xhr.responseText); // Log the error response
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'An error occurred while processing your request.',
                                });
                            }
                        });
                    }
                });

                // Delete Record
                setTimeout(() => {
                    document.querySelectorAll('.delete-record').forEach(function(element) {
                        element.addEventListener('click', function() {
                            var row = $('#datatables-basic').DataTable().row($(this)
                                .parents('tr'));
                            var data = row.data();
                            Swal.fire({
                                title: 'Apakah Anda yakin?',
                                text: "Data lokasi " + data.lokasi_nama +
                                    " akan dihapus!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ya, hapus!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: '{{ route('master.lokasi.destroy', ['id' => ':id']) }}'
                                            .replace(':id', data
                                                .id),
                                        type: "DELETE",
                                        data: {
                                            _token: "{{ csrf_token() }}"
                                        },
                                        success: function(
                                            response) {
                                            if (response
                                                .status ==
                                                'success') {
                                                $('#datatables-basic')
                                                    .DataTable()
                                                    .ajax
                                                    .reload();
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Success',
                                                    text: response
                                                        .message,
                                                });
                                            } else {
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Error',
                                                    text: response
                                                        .message,
                                                });
                                            }
                                        },
                                        error: function(xhr) {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error',
                                                text: 'An error occurred while processing your request.',
                                            });
                                        }
                                    });
                                }
                            });
                        });
                    });
                }, 200);
            });
        });
    </script>
@endsection

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master /</span> Lokasi</h4>

    <!-- DataTable with Buttons -->
    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="table border-top" id="datatables-basic">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Jenis</th>
                        <th>Lapisan Pengaman</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <!-- Modal to add new record -->
    <div class="offcanvas offcanvas-end" id="add-new-record">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="exampleModalLabel">{{ __('Data Baru') }}</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"
                id="new-record-close"></button>
        </div>
        <div class="offcanvas-body flex-grow-1">
            <form class="add-new-record pt-0 row g-2" id="form-add-new-record">
                <div class="col-sm-12">
                    <label class="form-label" for="lokasi_kode">{{ __('Kode Lokasi') }}</label>
                    <div class="input-group input-group-merge">
                        <input type="text" id="lokasi_kode" class="form-control dt-kode-lokasi" name="lokasi_kode"
                            placeholder="XX" aria-label="XX" required />
                    </div>
                </div>
                <div class="col-sm-12">
                    <label class="form-label" for="lokasi_nama">{{ __('Nama Lokasi') }}</label>
                    <div class="input-group input-group-merge">
                        <input type="text" id="lokasi_nama" name="lokasi_nama" class="form-control dt-nama-lokasi"
                            placeholder="HRD" aria-label="HRD" required />
                    </div>
                </div>
                <div class="col-sm-12">
                    <label class="form-label" for="lokasi_jenis">{{ __('Jenis Lokasi') }}</label>
                    <div class="input-group input-group-merge">
                        <select class="form-select dt-jenis-lokasi" id="lokasi_jenis" name="lokasi_jenis"
                            aria-label="Pilih Jenis" required>
                            <option value="">Pilih Jenis</option>
                            <option value="1">Indoor</option>
                            <option value="2">Outdoor</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-12">
                    <label class="form-label" for="lokasi_lapisan_pengaman">{{ __('Ring Lapisan Pengaman') }}</label>
                    <div class="input-group input-group-merge">
                        <select class="form-select dt-lapisan-pengaman" id="lokasi_lapisan_pengaman" name="lokasi_lapisan_pengaman"
                            aria-label="Pilih Lapisan Pengaman" required>
                            <option value="">Pilih Lapisan Pengaman</option>
                            <option value="Ring I">Ring I</option>
                            <option value="Ring II">Ring II</option>
                            <option value="Ring III">Ring III</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary data-submit me-sm-3 me-1">{{ __('Simpan') }}</button>
                    <button type="reset" class="btn btn-outline-secondary"
                        data-bs-dismiss="offcanvas">{{ __('Batal') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal to update new record -->
    <div class="offcanvas offcanvas-end" id="update-record" tabindex="-1">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="exampleModalLabel">{{ __('Ubah Data') }}</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"
                id="edit-close"></button>
        </div>
        <div class="offcanvas-body flex-grow-1">
            <form class="update-record pt-0 row g-2" id="form-update-record">
                <div class="col-sm-12">
                    <input type="hidden" id="id-edit" class="form-control dt-id-edit-lokasi" name="id-edit" required
                        readonly />
                </div>
                <div class="col-sm-12">
                    <label class="form-label" for="lokasi_kode">{{ __('Kode Lokasi') }}</label>
                    <div class="input-group input-group-merge">
                        <input type="text" id="lokasi_kode" class="form-control dt-kode-lokasi" name="lokasi_kode"
                            placeholder="XX" aria-label="XX" required />
                    </div>
                </div>
                <div class="col-sm-12">
                    <label class="form-label" for="lokasi_nama">{{ __('Nama Lokasi') }}</label>
                    <div class="input-group input-group-merge">
                        <input type="text" id="lokasi_nama" name="lokasi_nama" class="form-control dt-nama-lokasi"
                            placeholder="HRD" aria-label="HRD" required />
                    </div>
                </div>
                <div class="col-sm-12">
                    <label class="form-label" for="lokasi_jenis">{{ __('Jenis Lokasi') }}</label>
                    <div class="input-group input-group-merge">
                        <select class="form-select dt-jenis-lokasi" id="lokasi_jenis" name="lokasi_jenis"
                            aria-label="Pilih Jenis" required>
                            <option value="">Pilih Jenis</option>
                            <option value="1">Indoor</option>
                            <option value="2">Outdoor</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-12">
                    <label class="form-label" for="lokasi_lapisan_pengaman">{{ __('Ring Lapisan Pengaman') }}</label>
                    <div class="input-group input-group-merge">
                        <select class="form-select dt-lapisan-pengaman" id="lokasi_lapisan_pengaman" name="lokasi_lapisan_pengaman"
                            aria-label="Pilih Lapisan Pengaman" required>
                            <option value="">Pilih Lapisan Pengaman</option>
                            <option value="Ring I">Ring I</option>
                            <option value="Ring II">Ring II</option>
                            <option value="Ring III">Ring III</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary data-submit me-sm-3 me-1">{{ __('Simpan') }}</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas"
                        aria-label="Close">{{ __('Batal') }}</button>
                </div>
            </form>
        </div>
    </div>
    <!--/ DataTable with Buttons -->
@endsection
