@extends('layouts/contentNavbarLayout')

@section('title', 'Inspeksi')

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
            // Select2 Country
            var select2 = $('.select2');
            if (select2.length) {
                select2.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: $this.find('option:selected').text(),
                        dropdownParent: $this.parent()
                    });
                });
            }

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
                            url: "{{ route('inspeksi.data') }}",
                            type: "GET",
                        },
                        columns: [{
                                data: 'id'
                            },
                            {
                                data: 'tanggal'
                            },
                            {
                                data: 'pegawai'
                            },
                            {
                                data: 'hama_id'
                            },
                            {
                                data: 'hama.hama_nama'
                            },
                            {
                                data: 'jumlah'
                            },
                            {
                                data: ''
                            }
                        ],
                        columnDefs: [{
                                target: 3,
                                visible: false
                            },
                            {
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
                                className: 'create-new btn btn-primary',
                                action: function(e, dt, node, config) {
                                    window.location.href = '{{ route('inspeksi.create') }}';
                                }
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
                    $('div.head-label').html('<h5 class="card-title mb-0">{{ __('Data Inspeksi') }}</h5>');
                }
            });

            let fv, offCanvasEl, fvedit, offEditCanvasEl;

            $('#datatables-basic').on('draw.dt', function() {
                // edit record
                const formUpdateRecord = document.getElementById('form-update-record');

                setTimeout(() => {
                    const editRecord = document.querySelectorAll('.edit-record'),
                        offCanvasUpdateElement = document.querySelector('#update-record');
                    // To open offCanvas, to update new record
                    if (editRecord) {
                        editRecord.forEach(function(e) {
                            e.addEventListener('click', function() {
                                var row = $('#datatables-basic').DataTable().row($(
                                    this).parents('tr'));
                                var data = row.data();
                                window.location.href =
                                    '{{ route('inspeksi.edit', ['id' => ':id']) }}'
                                    .replace(':id', data.id);
                            });
                        });
                    }
                }, 300);

                // Delete Record
                setTimeout(() => {
                    document.querySelectorAll('.delete-record').forEach(function(element) {
                        element.addEventListener('click', function() {
                            var row = $('#datatables-basic').DataTable().row($(this)
                                .parents('tr'));
                            var data = row.data();
                            Swal.fire({
                                title: 'Apakah Anda yakin?',
                                text: "Data Inspeksi tanggal " + data
                                    .tanggal +
                                    " akan dihapus!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ya, hapus!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: '{{ route('inspeksi.destroy', ['id' => ':id']) }}'
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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Transaksi /</span> Inspeksi</h4>

    <!-- DataTable with Buttons -->
    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="table border-top" id="datatables-basic">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Tanggal</th>
                        <th>Pegawai</th>
                        <th style="display: none">ID_Hama</th>
                        <th>Hama</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <!--/ DataTable with Buttons -->
@endsection
