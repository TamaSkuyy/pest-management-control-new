@extends('layouts/contentNavbarLayout')

@section('title', 'Users')

@section('vendor-style')
    @vite('resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss')
    @vite('resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss')
    @vite('resources/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.scss')
    @vite('resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss')
    <!-- Row Group CSS -->
    @vite('resources/assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.scss')

    @vite('resources/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')
    @vite('resources/assets/vendor/libs/sweetalert2/sweetalert2.scss')
@endsection

@section('vendor-script')
    @vite('resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')
    @vite('resources/assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')
    @vite('resources/assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')
    @vite('resources/assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')
    @vite('resources/assets/vendor/libs/sweetalert2/sweetalert2.js')
@endsection

@section('page-script')
    {{-- @vite('resources/js/metode.js') --}}
    <script type="text/javascript">
        'use strict';
        // import { route } from 'ziggy-js';

        // datatable (jquery)
        document.addEventListener('DOMContentLoaded', function() {

            $(function() {
                var dt_basic_table = $('#datatables-basic'),
                    // var dt_basic_table = $('#datatables'),
                    dt_basic;

                // DataTable with buttons
                // --------------------------------------------------------------------

                if (dt_basic_table.length) {
                    dt_basic = dt_basic_table.DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: route('admin.users.data'),
                            type: 'GET'
                        },
                        columns: [{
                                data: 'id'
                            },
                            {
                                data: 'name'
                            },
                            {
                                data: 'email'
                            },
                            {
                                data: 'role'
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
                                title: "{{ __('Aksi') }}",
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
                    $('div.head-label').html('<h5 class="card-title mb-0">{{ __('Data User') }}</h5>');
                }

                // Add New record
                // On form submit, if form is valid
                fv.on('core.form.valid', function() {
                    var new_name = $('.add-new-record .dt-nama').val(),
                        new_email = $('.add-new-record .dt-email').val(),
                        new_role = $('.add-new-record .dt-role').val(),
                        new_password = $('.add-new-record .dt-password').val();

                    if (new_name != '') {
                        $.ajax({
                            url: "{{ route('admin.users.store') }}",
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                name: new_name,
                                email: new_email,
                                role: new_role,
                                password: new_password
                            },
                            success: function(response) {
                                // Check the response in the console
                                // console.log(response);
                                if (response.status == 'success') {
                                    // Handle success
                                    $('#datatables-basic').DataTable().ajax.reload();
                                    $('.add-new-record .dt-nama').val('');
                                    $('.add-new-record .dt-email').val('');
                                    $('.add-new-record .dt-jenis').val('');
                                    $('.add-new-record .dt-password').val('');
                                    offCanvasEl.hide();
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: response.message
                                    });
                                } else {
                                    // Handle error
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.message
                                    });
                                }
                            },
                            error: function(xhr) {
                                // Handle server errors
                                console.log(xhr.responseText); // Log the error response
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'An error occurred while processing your request.'
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
                            offCanvasElement.querySelectorAll('input').forEach(el =>
                                (el.value =
                                    ''));
                            offCanvasEl.show();
                        });
                    }
                }, 200);

                // Form validation for Add new record
                fv = FormValidation.formValidation(formAddNewRecord, {
                    fields: {
                        name: {
                            validators: {
                                notEmpty: {
                                    message: "{{ __('Nama perlu diisi') }}"
                                }
                            }
                        },
                        email: {
                            validators: {
                                notEmpty: {
                                    message: "{{ __('Email perlu diisi') }}"
                                },
                                emailAddress: {
                                    message: "{{ __('Email tidak valid') }}"
                                }
                            }
                        },
                        role: {
                            validators: {
                                notEmpty: {
                                    message: "{{ __('Role perlu dipilih') }}"
                                }
                            }
                        },
                        password: {
                            validators: {
                                notEmpty: {
                                    message: "{{ __('Password perlu diisi') }}"
                                },
                                stringLength: {
                                    min: 4,
                                    max: 100,
                                    message: "{{ __('Password harus memiliki minimal 4 karakter') }}"
                                },
                                // regexp: {
                                //     regexp: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,100}$/,
                                //     message: "{{ __('Password harus memiliki huruf besar, huruf kecil, angka dan karakter spesial') }}"
                                // }
                            }
                        }
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap5: new FormValidation.plugins.Bootstrap5({
                            eleValidClass: '',
                            rowSelector: '.col-sm-12'
                        }),
                        submitButton: new FormValidation.plugins.SubmitButton(),
                        autoFocus: new FormValidation.plugins.AutoFocus()
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
                                        this)
                                    .parents('tr')).data();
                                offCanvasUpdateElement.querySelectorAll('input')
                                    .forEach(el => (
                                        el.value = ''));
                                offCanvasUpdateElement.querySelector(
                                        '.dt-id-edit-user')
                                    .value = data.id;
                                offCanvasUpdateElement.querySelector(
                                        '.dt-nama').value =
                                    data.name;
                                offCanvasUpdateElement.querySelector(
                                        '.dt-email').value =
                                    data.email;
                                offCanvasUpdateElement.querySelector(
                                        '.dt-role').value =
                                    data.role;
                                offEditCanvasEl.show();
                            });
                        });
                    }
                }, 300);

                // Form validation for Edit record
                fvedit = FormValidation.formValidation(formUpdateRecord, {
                    fields: {
                        fields: {
                            name: {
                                validators: {
                                    notEmpty: {
                                        message: "{{ __('Nama perlu diisi') }}"
                                    }
                                }
                            },
                            email: {
                                validators: {
                                    notEmpty: {
                                        message: "{{ __('Email perlu diisi') }}"
                                    },
                                    emailAddress: {
                                        message: "{{ __('Email tidak valid') }}"
                                    }
                                }
                            },
                            role: {
                                validators: {
                                    notEmpty: {
                                        message: "{{ __('Role perlu dipilih') }}"
                                    }
                                }
                            },
                            password: {
                                validators: {
                                    stringLength: {
                                        min: 4,
                                        max: 100,
                                        message: "{{ __('Password harus memiliki minimal 4 karakter') }}"
                                    },
                                    // regexp: {
                                    //     regexp: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,100}$/,
                                    //     message: "{{ __('Password harus memiliki huruf besar, huruf kecil, angka dan karakter spesial') }}"
                                    // }
                                }
                            }
                        }
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap5: new FormValidation.plugins.Bootstrap5({
                            eleValidClass: '',
                            rowSelector: '.col-sm-12'
                        }),
                        submitButton: new FormValidation.plugins.SubmitButton(),
                        autoFocus: new FormValidation.plugins.AutoFocus()
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
                    var id = $('.update-record .dt-id-edit-user').val(),
                        name = $('.update-record .dt-nama').val(),
                        email = $('.update-record .dt-email').val(),
                        role = $('.update-record .dt-role').val();
                    password = $('.update-record .dt-password').val();

                    if (name != '') {
                        $.ajax({
                            url: "{{ route('admin.users.update', ['id' => ':id']) }}"
                                .replace(':id',
                                    id),
                            type: 'PUT',
                            data: {
                                _token: '{{ csrf_token() }}',
                                name: name,
                                email: email,
                                role: role,
                                password: password
                            },
                            success: function(response) {
                                // console.log(response); // Check the response in the console
                                if (response.status == 'success') {
                                    // Handle success
                                    $('#datatables-basic').DataTable().ajax.reload();
                                    $('.update-record .dt-id-edit-user').val('');
                                    $('.update-record .dt-nama').val('');
                                    $('.update-record .dt-email').val('');
                                    $('.update-record .dt-role').val('');
                                    $('.update-record .dt-password').val('');
                                    offEditCanvasEl.hide();
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: response.message
                                    });
                                } else {
                                    // Handle error
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.message
                                    });
                                }
                            },
                            error: function(xhr) {
                                // Handle server errors
                                console.log(xhr.responseText); // Log the error response
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'An error occurred while processing your request.'
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
                                .parents(
                                    'tr'));
                            var data = row.data();
                            Swal.fire({
                                title: 'Apakah Anda yakin?',
                                text: 'Data User dengan email ' + data
                                    .email +
                                    ' akan dihapus!',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ya, hapus!'
                            }).then(result => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: "{{ route('admin.users.destroy', ['id' => ':id']) }}"
                                            .replace(':id', data
                                                .id),
                                        type: 'DELETE',
                                        data: {
                                            _token: '{{ csrf_token() }}'
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
                                                        .message
                                                });
                                            } else {
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Error',
                                                    text: response
                                                        .message
                                                });
                                            }
                                        },
                                        error: function(xhr) {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error',
                                                text: 'An error occurred while processing your request.'
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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">User /</span> User</h4>

    <!-- DataTable with Buttons -->
    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="table border-top" id="datatables-basic">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
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
                    <label class="form-label" for="name">{{ __('Nama') }}</label>
                    <div class="input-group input-group-merge">
                        <input type="text" id="name" class="form-control dt-nama" name="name" placeholder="John"
                            aria-label="John" required />
                    </div>
                </div>
                <div class="col-sm-12">
                    <label class="form-label" for="email">{{ __('Email') }}</label>
                    <div class="input-group input-group-merge">
                        <input type="text" id="email" name="email" class="form-control dt-email"
                            placeholder="test@email.com" aria-label="test@email.com" required />
                    </div>
                </div>
                <div class="col-sm-12">
                    <label class="form-label" for="role">{{ __('Role') }}</label>
                    <div class="input-group input-group-merge">
                        <select class="form-select dt-role" id="role" name="role" aria-label="Pilih Role" required>
                            <option value="">Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-12">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-group input-group-merge">
                        <input type="password" class="form-control dt-password" id="password" name="password"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                        <span class="input-group-text cursor-pointer" id="password-toogle"><i class="bx bx-hide"></i></span>
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
                    <input type="hidden" id="id-edit" class="form-control dt-id-edit-user" name="id-edit" required
                        readonly />
                </div>
                <div class="col-sm-12">
                    <label class="form-label" for="name">{{ __('Nama') }}</label>
                    <div class="input-group input-group-merge">
                        <input type="text" id="name" class="form-control dt-nama" name="name" placeholder="John"
                            aria-label="John" required />
                    </div>
                </div>
                <div class="col-sm-12">
                    <label class="form-label" for="email">{{ __('Email') }}</label>
                    <div class="input-group input-group-merge">
                        <input type="text" id="email" name="email" class="form-control dt-email"
                            placeholder="test@email.com" aria-label="test@email.com" required />
                    </div>
                </div>
                <div class="col-sm-12">
                    <label class="form-label" for="role">{{ __('Role') }}</label>
                    <div class="input-group input-group-merge">
                        <select class="form-select dt-role" id="role" name="role" aria-label="Pilih Role"
                            required>
                            <option value="">Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-12">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-group input-group-merge">
                        <input type="password" class="form-control dt-password" id="password" name="password"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                        <span class="input-group-text cursor-pointer" id="password-toogle"><i
                                class="bx bx-hide"></i></span>
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
