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
    @vite('resources/assets/vendor/libs/select2/select2.scss')
    {{-- @vite('resources/assets/vendor/libs/bootstrap-select/bootstrap-select.scss') --}}
@endsection

@section('vendor-script')
    @vite('resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')
    {{-- @vite('resources/assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')
    @vite('resources/assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')
    @vite('resources/assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') --}}
    @vite('resources/assets/vendor/libs/sweetalert2/sweetalert2.js')
    @vite('resources/assets/vendor/libs/select2/select2.js')
    {{-- @vite('resources/assets/vendor/libs/bootstrap-select/bootstrap-select.js') --}}
@endsection

@section('page-script')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            // Select2
            var select2_metode = $('.select2_metode');
            if (select2_metode.length) {
                select2_metode.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih Metode',
                        dropdownParent: $this.parent(),
                        ajax: {
                            url: '/master/metode/select2',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    search: params.term
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: data
                                };
                            },
                            cache: true
                        }
                    });
                });
            }

            var select2_lokasi = $('.select2_lokasi');
            if (select2_lokasi.length) {
                select2_lokasi.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih Lokasi',
                        dropdownParent: $this.parent(),
                        ajax: {
                            url: '/master/lokasi/select2',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                var metode_value = select2_metode.val();
                                return {
                                    search: params.term,
                                    // metode_value: metode_value
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: data
                                };
                            },
                            cache: true
                        }
                    });
                });

            }

            var select2_hama = $('.select2_hama');
            if (select2_hama.length) {
                select2_hama.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih Hama',
                        dropdownParent: $this.parent(),
                        ajax: {
                            url: '/master/hama/select2',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                var metode_value = select2_metode.val();
                                return {
                                    search: params.term,
                                    metode_value: metode_value
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: data
                                };
                            },
                            cache: true
                        }
                    }).on('change', function() {
                        let hamaId = $(this).val();
                        // loadTindakan(hamaId);
                    });
                });

            }

            // Wizard Validation
            // --------------------------------------------------------------------
            const wizardValidation = document.querySelector('#wizard-validation');
            if (typeof wizardValidation !== undefined && wizardValidation !== null) {
                // Wizard form
                const wizardValidationForm = wizardValidation.querySelector('#wizard-validation-form');
                // Wizard steps
                const wizardValidationFormStep1 = wizardValidationForm.querySelector('#inspeksi-validation');
                // Wizard next prev button
                const wizardValidationNext = [].slice.call(wizardValidationForm.querySelectorAll('.btn-next'));
                const wizardValidationPrev = [].slice.call(wizardValidationForm.querySelectorAll('.btn-prev'));

                const validationStepper = new Stepper(wizardValidation, {
                    linear: true
                });

                // Inspeksi
                const FormValidation1 = formValidation(wizardValidationFormStep1, {
                    fields: {
                        metode_id: {
                            validators: {
                                notEmpty: {
                                    message: 'Metode perlu dipilih'
                                }
                            }
                        },
                        lokasi_id: {
                            validators: {
                                notEmpty: {
                                    message: 'Lokasi perlu dipilih'
                                }
                            }
                        },
                        hama_id: {
                            validators: {
                                callback: {
                                    message: 'Hama perlu dipilih',
                                    callback: function(input) {
                                        const metodeId = document.querySelector('#metode_id').value;
                                        if (metodeId != 3) {
                                            return input.value !== '';
                                        }
                                        return true;
                                    }
                                }
                            }
                        },
                        tanggal: {
                            validators: {
                                date: {
                                    format: 'YYYY-MM-DD',
                                    message: 'Format Tanggal Tidak Sesuai'
                                },
                                notEmpty: {
                                    message: 'Tanggal perlu diisi'
                                }
                            }
                        },
                        pegawai: {
                            validators: {
                                notEmpty: {
                                    message: 'Pegawai perlu diisi'
                                }
                            }
                        },
                        jumlah: {
                            validators: {
                                notEmpty: {
                                    message: 'Jumlah perlu diisi'
                                },
                                numeric: {
                                    message: 'Jumlah harus berupa angka'
                                }
                            }
                        }
                    },
                    plugins: {
                        trigger: new Trigger(),
                        bootstrap5: new Bootstrap5({
                            // Use this for enabling/changing valid/invalid class
                            // eleInvalidClass: '',
                            eleValidClass: '',
                            rowSelector: '.col-sm-6, .col-sm-4'
                        }),
                        autoFocus: new AutoFocus(),
                        submitButton: new SubmitButton()
                    },
                    init: instance => {
                        instance.on('plugins.message.placed', function(e) {
                            //* Move the error message out of the `input-group` element
                            if (e.element.parentElement.classList.contains('input-group')) {
                                e.element.parentElement.insertAdjacentElement('afterend', e
                                    .messageElement);
                            }
                        });
                    }
                }).on('core.form.valid', function() {
                    // Jump to the next step when all fields in the current step are valid
                    // wizardValidationNext.forEach(item => {
                    //     item.addEventListener('click', event => {
                    //         // When click the Next button, we will validate the current step
                    //         switch (validationStepper._currentIndex) {
                    //             case 0:
                    //                 validationStepper.next();
                    //                 break;

                    //             case 1:
                    //                 validationStepper.next();
                    //                 break;

                    //             default:
                    //                 break;
                    //         }
                    //     });
                    // });
                    validationStepper.next();
                });

                // select2
                if (select2_metode.length) {
                    select2_metode.each(function() {
                        var $this = $(this);
                        $this.wrap('<div class="position-relative"></div>');
                        $this
                            .select2({
                                placeholder: 'Pilih Metode',
                                dropdownParent: $this.parent(),
                                ajax: {
                                    url: '/master/metode/select2',
                                    dataType: 'json',
                                    delay: 250,
                                    data: function(params) {
                                        return {
                                            search: params.term
                                        };
                                    },
                                    processResults: function(data) {
                                        return {
                                            results: data
                                        };
                                    },
                                    cache: true
                                }
                            })
                            .on('change.select2', function() {
                                // Revalidate the color field when an option is chosen
                                // FormValidation1.revalidateField('metode_id');
                                const selectedValue = this.value;

                                if (selectedValue != 3) {
                                    $('.select2_hama').prop('disabled', false);
                                    $('#tindakan-rbt-div').hide();
                                    $('#tindakan-table-div').show();
                                } else {
                                    $(".select2_hama").val('').trigger('change')
                                    $('.select2_hama').prop('disabled', true);
                                    $('#tindakan-rbt-div').show();
                                    $('#tindakan-table-div').hide();
                                }
                            });
                    });
                }

                if (select2_lokasi.length) {
                    select2_lokasi.each(function() {
                        var $this = $(this);
                        $this.wrap('<div class="position-relative"></div>');
                        $this
                            .select2({
                                placeholder: 'Pilih Lokasi',
                                dropdownParent: $this.parent(),
                                ajax: {
                                    url: '/master/lokasi/select2',
                                    dataType: 'json',
                                    delay: 250,
                                    data: function(params) {
                                        var metode_value = select2_metode.val();
                                        return {
                                            search: params.term,
                                            // metode_value: metode_value
                                        };
                                    },
                                    processResults: function(data) {
                                        return {
                                            results: data
                                        };
                                    },
                                    cache: true
                                }
                            })
                            .on('change.select2', function() {
                                // Revalidate the color field when an option is chosen
                                // FormValidation1.revalidateField('lokasi_id');
                            });
                    });
                }

                if (select2_hama.length) {
                    select2_hama.each(function() {
                        var $this = $(this);
                        $this.wrap('<div class="position-relative"></div>');
                        $this
                            .select2({
                                placeholder: 'Pilih Hama',
                                dropdownParent: $this.parent(),
                                ajax: {
                                    url: '/master/hama/select2',
                                    dataType: 'json',
                                    delay: 250,
                                    data: function(params) {
                                        var metode_value = select2_metode.val();
                                        return {
                                            search: params.term,
                                            metode_value: metode_value
                                        };
                                    },
                                    processResults: function(data) {
                                        return {
                                            results: data
                                        };
                                    },
                                    cache: true
                                }
                            })
                    }).on('change.select2', function() {
                        let hamaId = $(this).val();
                        // loadTindakan(hamaId);
                        // Revalidate the color field when an option is chosen
                        // FormValidation1.revalidateField('hama_id');
                    });
                }

                wizardValidationNext.forEach(item => {
                    item.addEventListener('click', event => {
                        // When click the Next button, we will validate the current step
                        switch (validationStepper._currentIndex) {
                            case 0:
                                FormValidation1.validate();
                                break;

                            default:
                                break;
                        }
                    });
                });

                wizardValidationPrev.forEach(item => {
                    item.addEventListener('click', event => {
                        switch (validationStepper._currentIndex) {
                            case 2:
                                validationStepper.previous();
                                break;

                            case 1:
                                validationStepper.previous();
                                break;

                            case 0:

                            default:
                                break;
                        }
                    });
                });
            }

            let table = $('#tindakan-table').DataTable({
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'tindakan_id'
                    },
                    {
                        data: 'tindakan_nama'
                    },
                    {
                        data: 'tindakan_status'
                    }

                    // {
                    //     data: null,
                    //     render: function(data, type, row) {
                    //         return `
                //             <div class="form-check form-check-inline">
                //                 <input class="form-check-input" type="checkbox" value="${row.id}" id="tindakan-${row.id}">
                //                 <label class="form-check-label" for="tindakan-${row.id}">Pilih</label>
                //             </div>
                //         `;
                    //     }
                    // }
                ],
                pageLength: -1,
                paging: false,
                info: false,
                footer: false,
                columnDefs: [{
                    targets: [0, 1],
                    visible: false
                }],

            });

            // Fungsi untuk memuat data
            function loadTindakan(hamaId) {
                $.ajax({
                    url: '/inspeksi/datainspeksidetail/' + {{ $inspeksi->id }},
                    type: 'GET',
                    success: function(data) {
                        table.clear();
                        data.data.forEach(function(item) {
                            // Set checkbox checked based on tindakan_status
                            let isChecked = item.check == 1 ? ' checked' : '';
                            let isCheck = ' checked';

                            table.row.add({
                                'id': item.id,
                                'tindakan_id': item.tindakan_id,
                                'tindakan_nama': item.tindakan.tindakan_nama,
                                'tindakan_status': `<div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" value="${item.id}"
                                    id="tindakan-${item.id}" ${isCheck}>
                                    <label class="form-check-label" for="tindakan-${item.id}">Pilih</label>
                                  </div>`
                            });
                        });
                        table.draw();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading tindakan data:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Gagal memuat data tindakan',
                            icon: 'error'
                        });
                    }
                });
            }

            // Load initial data
            // loadTindakan();

            // Event simpan pada button btsimpan
            $('#btsimpan').on('click', function() {
                var tindakanIds = [];
                $.each(table.column(0).data(), function(index, value) {
                    var checked = $('#tindakan-' + value).is(':checked') ? 1 : 0;
                    tindakanIds.push({
                        id: value,
                        checked: checked
                    });
                });

                $.ajax({
                    url: '/inspeksi/store',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        metode_id: $('#metode_id').val(),
                        lokasi_id: $('#lokasi_id').val(),
                        hama_id: $('#hama_id').val(),
                        tanggal: $('#tanggal').val(),
                        pegawai: $('#pegawai').val(),
                        jumlah: $('#jumlah').val(),
                        bahan_aktif: $('#bahan_aktif').val(),
                        jumlah_bait: $('#jumlah_bait').val(),
                        // Khusus metode RBT
                        kondisi_umpan_utuh_bait: $('#kondisi_umpan_utuh_bait').val(),
                        kondisi_umpan_kurang_bait: $('#kondisi_umpan_kurang_bait').val(),
                        kondisi_umpan_rusak_bait: $('#kondisi_umpan_rusak_bait').val(),
                        tindakan_ganti_bait: $('#tindakan_ganti_bait').val(),
                        tindakan_tambah_bait: $('#tindakan_tambah_bait').val(),
                        kondisi_rbs: $('#kondisi_rbs').val(),
                        tindakan_rbs: $('#tindakan_rbs').val(),
                        //
                        tindakan: tindakanIds
                    },
                    success: function(data) {
                        Swal.fire({
                                title: 'Berhasil!',
                                text: 'Data inspeksi berhasil disimpan.',
                                icon: 'success'
                            })
                            .then(result => {
                                window.location.href = '/inspeksi';
                            });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Gagal menyimpan data inspeksi.',
                            icon: 'error'
                        });
                    }
                });
            });

            // Set initial values for Select2 dropdowns
            var option_metode = new Option('{{ $inspeksi->metode->metode_nama }}', '{{ $inspeksi->metode_id }}',
                true, true);
            select2_metode.append(option_metode).trigger('change');

            var option_lokasi = new Option('{{ $inspeksi->lokasi->lokasi_nama }}', '{{ $inspeksi->lokasi_id }}',
                true, true);
            select2_lokasi.append(option_lokasi).trigger('change');

            var option_hama = new Option('{{ $inspeksi->hama->hama_nama ?? '' }}', '{{ $inspeksi->hama_id ?? '' }}',
                true, true);
            select2_hama.append(option_hama).trigger('change');

            // Set initial values for input fields

            @if ($inspeksi->metode_id == 3)
                $('#tindakan-rbt-div').show();
                $('#tindakan-table-div').hide();
                $('#bahan_aktif').val('{{ $inspeksi->inspeksidetail[0]->bahan_aktif }}');
                $('#jumlah_bait').val('{{ $inspeksi->inspeksidetail[0]->jumlah_bait }}');
                $('#kondisi_umpan_utuh_bait').val('{{ $inspeksi->inspeksidetail[0]->kondisi_umpan_utuh_bait }}');
                $('#kondisi_umpan_kurang_bait').val(
                    '{{ $inspeksi->inspeksidetail[0]->kondisi_umpan_kurang_bait }}');
                $('#kondisi_umpan_rusak_bait').val('{{ $inspeksi->inspeksidetail[0]->kondisi_umpan_rusak_bait }}');
                $('#tindakan_ganti_bait').val('{{ $inspeksi->inspeksidetail[0]->tindakan_ganti_bait }}');
                $('#tindakan_tambah_bait').val('{{ $inspeksi->inspeksidetail[0]->tindakan_tambah_bait }}');
                $('#kondisi_rbs').val('{{ $inspeksi->inspeksidetail[0]->kondisi_rbs }}');
                $('#tindakan_rbs').val('{{ $inspeksi->inspeksidetail[0]->tindakan_rbs }}');
            @else
                $('#tindakan-rbt-div').hide();
                $('#tindakan-table-div').show();
                loadTindakan('{{ $inspeksi->hama_id }}');
            @endif
        });
    </script>
@endsection


@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Inspeksi /</span> Update Data</h4>

        <!-- Default -->
        <div class="row">

            <!-- Validation Wizard -->
            <div class="col-12 mb-4">
                <small class="text-light fw-semibold"></small>
                <div id="wizard-validation" class="bs-stepper mt-2">
                    <div class="bs-stepper-header">
                        <div class="step" data-target="#inspeksi-validation">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle">1</span>
                                <span class="bs-stepper-label mt-1">
                                    <span class="bs-stepper-title">Inspeksi</span>
                                </span>
                            </button>
                        </div>
                        <div class="line">
                            <i class="bx bx-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#personal-info-validation">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle">2</span>
                                <span class="bs-stepper-label mt-1">
                                    <span class="bs-stepper-title">Tindakan</span>
                                    <span class="bs-stepper-subtitle">Input data tindakan dari inspeksi</span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="bs-stepper-content">
                        <form id="wizard-validation-form" onSubmit="return false">
                            <!-- Inspeksi -->
                            <div id="inspeksi-validation" class="content">
                                <div class="content-header mb-3">
                                    <h6 class="mb-0">Inspeksi</h6>
                                    <small>Data Inspeksi.</small>
                                </div>
                                <input type="hidden" name="id" id="id" value="{{ $inspeksi->id }}" />
                                <div class="row g-3">
                                    <div class="col-sm-4">
                                        <label class="form-label" for="metode_id">Metode</label>
                                        <select class="select2_metode" id="metode_id" name="metode_id">
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-label" for="lokasi_id">Lokasi</label>
                                        <select class="select2_lokasi" id="lokasi_id" name="lokasi_id">
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-label" for="hama_id">Hama</label>
                                        <select class="select2_hama" id="hama_id" name="hama_id">
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-label" for="tanggal">Tanggal</label>
                                        <input type="date" name="tanggal" id="tanggal" class="form-control"
                                            value="{{ $inspeksi->tanggal ?? date('Y-m-d') }}" placeholder="" />
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-label" for="pegawai">Pegawai</label>
                                        <input type="text" name="pegawai" id="pegawai" class="form-control"
                                            placeholder="Nama Pegawai" value="{{ $inspeksi->pegawai ?? '' }}" />
                                    </div>
                                    <div class="col-sm-4 form-password-toggle">
                                        <label class="form-label" for="jumlah">Jumlah</label>
                                        <input type="number" id="jumlah" name="jumlah" class="form-control"
                                            min="0" value="{{ $inspeksi->jumlah ?? 0 }}" />
                                    </div>
                                    <div class="col-12 d-flex justify-content-between">
                                        <button class="btn btn-label-secondary btn-prev" disabled>
                                            <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <button class="btn btn-primary btn-next">
                                            <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span>
                                            <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- Tindakan -->
                            <div id="personal-info-validation" class="content">
                                <div class="content-header mb-3">
                                    <h6 class="mb-0">Tindakan</h6>
                                    <small>Data tindakan inspeksi.</small>
                                </div>
                                <div class="row g-3">
                                    <div class="col-sm-12">
                                        <div class="col-sm-12" id="tindakan-rbt-div">
                                            <div class="row g-3">
                                                <div class="col-sm-6">
                                                    <label class="form-label" for="bahan_aktif">Bahan Aktif</label>
                                                    <input type="text" name="bahan_aktif" id="bahan_aktif"
                                                        class="form-control" placeholder="Bahan Aktif" />
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="form-label" for="jumlah_bait">Jumlah Bait</label>
                                                    <input type="text" name="jumlah_bait" id="jumlah_bait"
                                                        class="form-control" placeholder="Jumlah Bait" />
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="form-label" for="kondisi_umpan_utuh_bait">Kondisi Umpan
                                                        Utuh</label>
                                                    <input type="text" name="kondisi_umpan_utuh_bait"
                                                        id="kondisi_umpan_utuh_bait" class="form-control"
                                                        placeholder="Kondisi Umpan Utuh" />
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="form-label" for="kondisi_umpan_kurang_bait">Kondisi
                                                        Umpan Kurang</label>
                                                    <input type="text" name="kondisi_umpan_kurang_bait"
                                                        id="kondisi_umpan_kurang_bait" class="form-control"
                                                        placeholder="Kondisi Umpan Kurang" />
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="form-label" for="kondisi_umpan_rusak_bait">Kondisi Umpan
                                                        Rusak</label>
                                                    <input type="text" name="kondisi_umpan_rusak_bait"
                                                        id="kondisi_umpan_rusak_bait" class="form-control"
                                                        placeholder="Kondisi Umpan Rusak" />
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="form-label" for="tindakan_ganti_bait">Tindakan Ganti
                                                        Bait</label>
                                                    <input type="text" name="tindakan_ganti_bait"
                                                        id="tindakan_ganti_bait" class="form-control"
                                                        placeholder="Tindakan Ganti Bait" />
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="form-label" for="tindakan_tambah_bait">Tindakan Tambah
                                                        Bait</label>
                                                    <input type="text" name="tindakan_tambah_bait"
                                                        id="tindakan_tambah_bait" class="form-control"
                                                        placeholder="Tindakan Tambah Bait" />
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="form-label" for="kondisi_rbs">Kondisi RBS</label>
                                                    <select name="kondisi_rbs" id="kondisi_rbs" class="form-select">
                                                        <option value="">Pilih Kondisi RBS</option>
                                                        <option value="OK">OK</option>
                                                        <option value="RUSAK">RUSAK</option>
                                                        <option value="HILANG">HILANG</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="form-label" for="tindakan_rbs">Tindakan RBS</label>
                                                    <select name="tindakan_rbs" id="tindakan_rbs" class="form-select">
                                                        <option value="">Pilih Tindakan RBS</option>
                                                        <option value="GANTI">GANTI</option>
                                                        <option value="CLEANING">CLEANING</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12" id="tindakan-table-div">
                                            <table id="tindakan-table" class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>id</th>
                                                        <th>Tindakan_ID</th>
                                                        <th>Tindakan</th>
                                                        <th>Checklist</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Data akan diisi oleh DataTables -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-between">
                                        <button class="btn btn-primary btn-prev">
                                            <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <button class="btn btn-success btn-next btn-submit" id="btsimpan"
                                            name="btsimpan">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /Validation Wizard -->
        </div>
    </div>
@endsection
