@extends('layouts/blankLayout')

@section('title', 'Welcome')

@section('page-style')
    @vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
    <div class="authentication-wrapper authentication-cover">
        <div class="authentication-inner row m-0">
            <!-- /Left Text -->
            <div class="d-none d-lg-flex col-lg-7 col-xl-8 p-0">
                <div class="w-100">
                    <img src="{{ asset('assets/img/backgrounds/background.jpg') }}"
                        class="img-fluid w-100 h-100 object-fit-cover" style="min-height: 100vh;" alt="Login image"
                        data-app-dark-img="{{ asset('assets/img/backgrounds/background.jpg') }}"
                        data-app-light-img="{{ asset('assets/img/backgrounds/background.jpg') }}" />
                </div>
            </div>

            <!-- Login -->
            <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-3 p-sm-4 p-lg-5">
                <div class="w-100 mx-auto" style="max-width: 400px;">
                    <!-- Logo Header with animation -->
                    <div
                        class="d-flex justify-content-center align-items-center mb-4 animate__animated animate__fadeIn gap-4">
                        <img src="{{ asset('assets/img/icons/Logo_Orangtua.png') }}" class="img-fluid hover-scale"
                            alt="shield" width="150" style="transition: transform 0.3s ease;">
                        <img src="{{ asset('assets/img/icons/logo_k3.png') }}" class="img-fluid hover-scale" alt="bug"
                            width="150" style="transition: transform 0.3s ease;">
                    </div>

                    <!-- Main Heading with enhanced gradient and animations -->
                    <div class="text-center mb-4">
                        <h2 class="app-heading fs-3 animate__animated animate__fadeInUp"
                            style="background: linear-gradient(45deg, #696cff, #4d4ef3);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        font-weight: 800;
                        letter-spacing: 0.5px;
                        text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
                        margin-bottom: 0.5rem;">
                            Pest Management Control
                        </h2>
                        <p class="text-body fw-semibold mb-0 animate__animated animate__fadeInUp"
                            style="animation-delay: 0.2s;
                        font-size: 1.1rem;
                        color: #696cff !important;
                        letter-spacing: 0.3px;">
                            PT. SAVANA TIRTA MAKMUR
                        </p>
                    </div>

                    <!-- Welcome Text -->
                    <div class="animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                        {{-- <h4 class="mb-2 text-primary fs-5">{{ __('Selamat Datang') }}! 👋</h4>
                      <p class="mb-4 text-muted small">{{ __('Silahkan login untuk melanjutkan.') }}</p> --}}
                    </div>

                    <!-- Login Form with enhanced styling -->
                    <form id="formAuthentication" class="mb-3 animate__animated animate__fadeInUp"
                        style="animation-delay: 0.4s; background: rgba(255, 255, 255, 0.9); padding: 20px; border-radius: 15px; box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);"
                        method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email"
                                class="form-label text-primary small">{{ __('Email atau Username') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email"
                                placeholder="Enter your email or username"
                                style="border-radius: 10px; transition: all 0.3s ease; background: rgba(255, 255, 255, 0.8);"
                                autofocus>

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
                                    style="border-radius: 10px; transition: all 0.3s ease; background: rgba(255, 255, 255, 0.8);"
                                    aria-describedby="password" />

                                <span class="input-group-text cursor-pointer" style="background: rgba(255, 255, 255, 0.8);">
                                    <i class="bx bx-hide"></i>
                                </span>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <button class="btn btn-primary w-100"
                            style="border-radius: 10px; transition: all 0.3s ease; background: linear-gradient(135deg, #696cff 0%, #4d4ef3 100%); border: none;">
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
