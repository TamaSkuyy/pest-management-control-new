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
                                    <div class="row p-3 m-3">
                                        <!-- Line Chart -->
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between">
                                                <div>
                                                    <h5 class="card-title mb-0">Inspeksi</h5>
                                                    <small class="text-muted">Data Inspeksi per bulan</small>
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
                                    <!-- Donut Chart -->
                                    <div class="row p-3 m-3">
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between">
                                                <div>
                                                    <h5 class="card-title mb-0">Distribusi Inspeksi</h5>
                                                    <small class="text-muted">Berdasarkan Metode Inspeksi</small>
                                                </div>
                                                {{-- filter month and year --}}
                                                <div class="d-flex">
                                                    <div class="me-2">
                                                        <select id="monthFilterDonutChart" class="form-select">
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
                                                        <select id="yearFilterDonutChart" class="form-select">
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
                                                <div id="donutChart"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <!-- Pie Chart -->
                                    <div class="row p-3 m-3">
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between">
                                                <div>
                                                    <h5 class="card-title mb-0">Data Lokasi</h5>
                                                    <small class="text-muted">Per Metode dan Total</small>
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
                                                        <div id="pieChartPGT"></div>
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                        <div id="pieChartTotal"></div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div id="pieChartFS"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /Pie Chart -->
                                </div>
                                <div class="swiper-slide">
                                    <!-- Line Chart -->
                                    <div class="row p-3 m-3">
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between">
                                                <div>
                                                    <h5 class="card-title mb-0">Data Hama</h5>
                                                    <small class="text-muted">Per Kondisi Bait</small>
                                                </div>
                                                <div>
                                                    <select id="yearFilterBarChart" class="form-select">
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
                                                <div id="barChart"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /Line Chart -->
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
                        <span id="bulanInspeksi"></span>

                        <span id="tahunInspeksi"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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
