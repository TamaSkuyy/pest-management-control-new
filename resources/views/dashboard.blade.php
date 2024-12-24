@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard')

@section('vendor-style')
    @vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
    @vite('resources/assets/vendor/libs/swiper/swiper.scss')
@endsection

@section('vendor-script')
    @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
    {{-- @vite('resources/assets/vendor/libs/swiper/swiper.js') --}}
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
                                    <!-- Line Chart -->
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between">
                                            <div>
                                                <h5 class="card-title mb-0">Inspeksi</h5>
                                                <small class="text-muted">Data Inspeksi per bulan</small>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div id="lineChart"></div>
                                        </div>
                                    </div>
                                    <!-- /Line Chart -->
                                </div>
                                <div class="swiper-slide">
                                    <!-- Line Area Chart -->
                                    <div class="col-12 mb-4">
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between">
                                                <div>
                                                    <h5 class="card-title mb-0">Last updates</h5>
                                                    <small class="text-muted">Commercial networks</small>
                                                </div>
                                                <div class="dropdown">
                                                    <button type="button" class="btn dropdown-toggle px-0"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bx bx-calendar"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a href="javascript:void(0);"
                                                                class="dropdown-item d-flex align-items-center">Today</a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0);"
                                                                class="dropdown-item d-flex align-items-center">Yesterday</a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0);"
                                                                class="dropdown-item d-flex align-items-center">Last 7
                                                                Days</a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0);"
                                                                class="dropdown-item d-flex align-items-center">Last 30
                                                                Days</a>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider" />
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0);"
                                                                class="dropdown-item d-flex align-items-center">Current
                                                                Month</a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0);"
                                                                class="dropdown-item d-flex align-items-center">Last
                                                                Month</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div id="lineAreaChart"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /Line Area Chart -->
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
@endsection
