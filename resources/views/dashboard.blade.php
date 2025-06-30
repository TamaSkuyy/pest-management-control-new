@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard')

@section('vendor-style')

    {{-- Datatables --}}
    @vite('resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss')
    @vite('resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss')
    @vite('resources/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.scss')
    @vite('resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss')
    {{-- End of Datatables --}}
    @vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
    @vite('resources/assets/vendor/libs/swiper/swiper.scss')
    <style>
        .apexcharts-canvas {
            cursor: pointer;
            z-index: 3;
            position: relative;
        }

        .swiper-container {
            z-index: 1;
        }

        #areaDetailsTable {
            width: 100%;
        }

        /* Chart Loading States */
        .chart-loading {
            position: relative;
            opacity: 0.6;
        }

        .chart-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chart-fade-in {
            animation: fadeInChart 0.5s ease-in;
        }

        @keyframes fadeInChart {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Tooltip Custom Styles */
        .tooltip-custom {
            background: #fff;
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            padding: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            min-width: 200px;
        }

        .tooltip-header {
            font-weight: 600;
            color: #5a5c69;
            margin-bottom: 8px;
            border-bottom: 1px solid #e3e6f0;
            padding-bottom: 6px;
        }

        .tooltip-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .tooltip-label {
            color: #858796;
            font-size: 13px;
        }

        .tooltip-value {
            color: #5a5c69;
            font-weight: 600;
            font-size: 13px;
        }
    </style>
@endsection

@section('vendor-script')
    @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
    {{-- @vite('resources/assets/vendor/libs/swiper/swiper.js') --}}
    @vite('resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')
@endsection

@section('page-script')
    @vite('resources/assets/js/dashboards-analytics.js')
    @vite('resources/js/dashboard.js')
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('Selamat Datang') }}, {{ Auth::user()->name }}!</h5>
                    <div class="col-12 mb-4">
                        <div class="swiper" id="swiper-with-pagination">
                            <div class="swiper-wrapper">
                                
                                <div class="swiper-slide">
                                    <!-- Pie Chart -->
                                    <div class="row p-3 m-3">
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between">
                                                <div>
                                                    <h5 class="card-title mb-0">Distribusi Lokasi</h5>
                                                    <small class="text-muted">Per Metode</small>
                                                </div>
                                                {{-- filter month and year --}}
                                                <div class="d-flex">
                                                    <div class="me-2">
                                                        <select id="monthFilterPieChart" class="form-select">
                                                            @php
                                                                $currentMonth = date('m');
                                                            @endphp
                                                            @for ($month = 1; $month <= 12; $month++)
                                                                <option value="{{ $month }}">
                                                                    {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                                                </option>
                                                            @endfor
                                                            <option value="all" selected>All Months</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <select id="yearFilterPieChart" class="form-select">
                                                            @php
                                                                $currentYear = date('Y');
                                                                $startYear = 2024; // You can adjust this starting year
                                                            @endphp
                                                            @for ($year = $currentYear; $year >= $startYear; $year--)
                                                                <option value="{{ $year }}"
                                                                    {{ $year == $currentYear ? 'selected' : '' }}>
                                                                    {{ $year }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div id="pieChartFS"></div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div id="pieChartPGT"></div>
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                        <div id="pieChartRbs"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /Pie Chart -->
                                </div>

                                <div class="swiper-slide">
                                    <div class="row p-3 m-3">
                                        <!-- Line Chart -->
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between">
                                                <div>
                                                    <h5 class="card-title mb-0">Distribusi Inspeksi</h5>
                                                    <small class="text-muted">Data Distribusi Inspeksi per bulan</small>
                                                </div>
                                                <div>
                                                    <select id="yearFilterLineChart" class="form-select">
                                                        @php
                                                            $currentYear = date('Y');
                                                            $startYear = 2024; // You can adjust this starting year
                                                        @endphp
                                                        @for ($year = $currentYear; $year >= $startYear; $year--)
                                                            <option value="{{ $year }}"
                                                                {{ $year == $currentYear ? 'selected' : '' }}>
                                                                {{ $year }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div id="lineChart"></div>
                                            </div>
                                        </div>
                                        <!-- /Line Chart -->
                                    </div>
                                </div>
                                
                                <div class="swiper-slide">
                                    <!-- Line Chart Hama -->
                                    <div class="row p-3 m-3">
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between">
                                                <div>
                                                    <h5 class="card-title mb-0">Data Jumlah dan Hama yang Terperangkap PGT & FS</h5>
                                                    <small class="text-muted">Berdasarkan Lapisan Pengaman</small>
                                                </div>
                                                {{-- filter month, year, and security layer --}}
                                                <div class="d-flex">
                                                    <div class="me-2">
                                                        <select id="lapisanPengamanFilterLineChartHama" class="form-select">
                                                            <option value="all" selected>Semua Lapisan</option>
                                                            <!-- Options will be populated dynamically -->
                                                        </select>
                                                    </div>
                                                    <div class="me-2">
                                                        <select id="monthFilterLineChartHama" class="form-select">
                                                            @php
                                                                $currentMonth = date('m');
                                                            @endphp
                                                            @for ($month = 1; $month <= 12; $month++)
                                                                <option value="{{ $month }}">
                                                                    {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                                                </option>
                                                            @endfor
                                                            <option value="all" selected>All Months</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <select id="yearFilterLineChartHama" class="form-select">
                                                            @php
                                                                $currentYear = date('Y');
                                                                $startYear = 2024; // You can adjust this starting year
                                                            @endphp
                                                            @for ($year = $currentYear; $year >= $startYear; $year--)
                                                                <option value="{{ $year }}"
                                                                    {{ $year == $currentYear ? 'selected' : '' }}>
                                                                    {{ $year }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div id="lineChartHama"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                <div class="swiper-slide">
                    <!-- Donut Chart Hama -->
                    <div class="row p-3 m-3">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title mb-0">Distribusi Hama Terperangkap</h5>
                                    <small class="text-muted">Top 10 Hama Berdasarkan Jumlah</small>
                                </div>
                                {{-- filter month, year, and security layer --}}
                                <div class="d-flex">
                                    <div class="me-2">
                                        <select id="lapisanPengamanFilterDonutHama" class="form-select">
                                            <option value="all" selected>Semua Lapisan</option>
                                            <!-- Options will be populated dynamically -->
                                        </select>
                                    </div>
                                    <div class="me-2">
                                        <select id="monthFilterDonutHama" class="form-select">
                                            @php
                                                $currentMonth = date('m');
                                            @endphp
                                            @for ($month = 1; $month <= 12; $month++)
                                                <option value="{{ $month }}">
                                                    {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                                </option>
                                            @endfor
                                            <option value="all" selected>All Months</option>
                                        </select>
                                    </div>
                                    <div>
                                        <select id="yearFilterDonutHama" class="form-select">
                                            @php
                                                $currentYear = date('Y');
                                                $startYear = 2024; // You can adjust this starting year
                                            @endphp
                                            @for ($year = $currentYear; $year >= $startYear; $year--)
                                                <option value="{{ $year }}"
                                                    {{ $year == $currentYear ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="donutChartHama"></div>
                            </div>
                        </div>
                    </div>
                    <!-- /Donut Chart Hama -->
                </div>

                            </div>
                            <div class="swiper-pagination"></div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="areaChartModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Area Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table id="areaDetailsTable" class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Value</th>
                                <th>Category</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Inspection -->
    <div class="modal fade" id="inspectionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Inspeksi
                        <span id="namaMetodeInspeksi"></span>
                        <span id="bulanInspeksi"></span>

                        <span id="tahunInspeksi"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-end mb-3">
                        <button id="showAllInspeksiBtn" class="btn btn-sm btn-outline-primary">Tampilkan Semua Metode</button>
                    </div>
                    <table id="inspeksiTable" class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Metode</th>
                                <th>Lokasi</th>
                                <th>Hama</th>
                                <th>Pegawai</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Inspection Metode -->
    <div class="modal fade" id="inspectionMetodeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Inspeksi Metode <span id="namaMetode"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table id="inspeksiMetodeTable" class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Metode</th>
                                <th>Lokasi</th>
                                <th>Hama</th>
                                <th>Pegawai</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Inspection Lokasi -->
    <div class="modal fade" id="inspectionLokasiModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Inspeksi Lokasi <span id="namaLokasi"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table id="inspeksiLokasiTable" class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Metode</th>
                                <th>Lokasi</th>
                                <th>Hama</th>
                                <th>Pegawai</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>

                        <tbody>
                            <!-- Add table rows here dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Inspection Kondisi Bait -->
    <div class="modal fade" id="inspectionKondisiBaitModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Inspeksi Kondisi Bait <span id="kondisiBait"></span> - Bulan <span
                            id="bulanKondisiBait"></span> - Tahun <span id="tahunKondisiBait"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table id="inspeksiKondisiBaitTable" class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Lokasi</th>
                                <th>Kondisi Bait</th>
                                <th>Tindakan</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
