@extends('layouts/blankLayout')

@section('title', 'Welcome')

@section('page-style')
    @vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
    <div class="authentication-wrapper authentication-cover">
        <div class="authentication-inner row m-0">
            <!-- /Left Text -->
            <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center p-5">
                <div class="w-100 d-flex justify-content-center">
                    <img src="{{ asset('assets/img/backgrounds/background.jpg') }}" class="img-fluid" alt="Login image"
                        data-app-dark-img="{{ asset('assets/img/backgrounds/background.jpg') }}"
                        data-app-light-img="{{ asset('assets/img/backgrounds/background.jpg') }}" />
                </div>
            </div>

            <!-- Login -->
            <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-3 p-sm-4 p-lg-5">
                <div class="w-100 mx-auto" style="max-width: 400px;">
                    <!-- Logo Header with animation -->
                    <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeIn">
                        <img src="{{ asset('assets/img/icons/Logo_Orangtua.png') }}" class="img-fluid hover-scale"
                            alt="shield" width="50" style="transition: transform 0.3s ease;">
                        <p class="text-body fw-semibold mb-0 text-center medium">PT. SAVANA TIRTA MAKMUR</p>
                        <img src="{{ asset('assets/img/icons/logo_k3.png') }}" class="img-fluid hover-scale" alt="bug"
                            width="50" style="transition: transform 0.3s ease;">
                    </div>

                    <!-- Main Heading with gradient -->
                    <h2 class="app-heading mb-4 text-center fs-3 animate__animated animate__fadeInUp"
                        style="background: linear-gradient(45deg, #696cff, #4d4ef3);
               -webkit-background-clip: text;
               -webkit-text-fill-color: transparent;
               font-weight: 700;">
                        Pest Management Control
                    </h2>

                    <!-- Welcome Text -->
                    <div class="animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                        <h4 class="mb-2 text-primary fs-5">{{ __('Selamat Datang') }}! 👋</h4>
                        <p class="mb-4 text-muted small">{{ __('Silahkan login untuk melanjutkan.') }}</p>
                    </div>

                    <!-- Login Form with smooth transitions -->
                    <form id="formAuthentication" class="mb-3 animate__animated animate__fadeInUp"
                        style="animation-delay: 0.4s" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email"
                                class="form-label text-primary small">{{ __('Email atau Username') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email"
                                placeholder="Enter your email or username"
                                style="border-radius: 10px; transition: all 0.3s ease;" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4 form-password-toggle">
                            <label class="form-label text-primary small" for="password">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="current-password" placeholder="············"
                                    style="border-radius: 10px; transition: all 0.3s ease;" aria-describedby="password" />

                                <span class="input-group-text cursor-pointer">
                                    <i class="bx bx-hide"></i>
                                </span>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <button class="btn btn-primary w-100" style="border-radius: 10px; transition: all 0.3s ease;">
                            Sign in
                        </button>
                    </form>
                </div>
            </div>

            <style>
                @media (max-width: 576px) {
                    .authentication-bg {
                        padding: 1rem !important;
                    }

                    .app-heading {
                        font-size: 1.5rem !important;
                    }
                }

                .hover-scale:hover {
                    transform: scale(1.1);
                }

                .form-control:focus {
                    box-shadow: 0 0 0 0.25rem rgba(105, 108, 255, 0.1);
                }

                .btn:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 5px 15px rgba(105, 108, 255, 0.4);
                }
            </style>
            <!-- /Login -->
        </div>
    </div>
@endsection
