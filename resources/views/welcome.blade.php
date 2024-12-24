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
                    <img src="{{ url('https://menjadipengaruh.com/wp-content/uploads/2024/01/269077.jpg') }}"
                        class="img-fluid" alt="Login image" data-app-dark-img="illustrations/boy-with-rocket-dark.png"
                        data-app-light-img="illustrations/boy-with-rocket-light.png" />
                </div>
            </div>
            <!-- /Left Text -->

            <!-- Login -->
            <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-sm-5 p-4">
                <div class="w-px-400 mx-auto">
                    <h2 class="app-heading text-body fw-bolder mb-5 text-primary fw-bold">Pest Management Control</h2>
                    <h4 class="mb-2">{{ __('Selamat Datang') }}! 👋</h4>
                    <p class="mb-4">{{ __('Silahkan login untuk melanjutkan.') }}</p>

                    <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email atau Username') }}</label>
                            {{-- <div class="col-md-6"> --}}
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email"
                                placeholder="Enter your email or username" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            {{-- </div> --}}
                            {{-- <input type="text" class="form-control" id="email" name="email-username"
                          placeholder="Enter your email or username" autofocus /> --}}
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password">Password</label>
                                {{-- <a href="auth-forgot-password-cover.html">
                              <small>Forgot Password?</small>
                          </a> --}}
                            </div>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control"
                                    @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="current-password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" />

                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            {{-- <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="remember-me" />
                          <label class="form-check-label" for="remember-me"> Remember Me </label>
                      </div> --}}
                        </div>
                        <button class="btn btn-primary d-grid w-100">Sign in</button>
                    </form>

                    {{-- <div class="card-body">
                  <form method="POST" action="{{ route('login') }}">
                      @csrf

                      <div class="row mb-3">
                          <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                          <div class="col-md-6">
                              <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                      name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                              @error('email')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                          </div>
                      </div>

                      <div class="row mb-3">
                          <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                          <div class="col-md-6">
                              <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                      name="password" required autocomplete="current-password">

                              @error('password')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                          </div>
                      </div>

                      <div class="row mb-3">
                          <div class="col-md-6 offset-md-4">
                              <div class="form-check">
                                  <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                          {{ old('remember') ? 'checked' : '' }}>

                                  <label class="form-check-label" for="remember">
                                      {{ __('Remember Me') }}
                                  </label>
                              </div>
                          </div>
                      </div>

                      <div class="row mb-0">
                          <div class="col-md-8 offset-md-4">
                              <button type="submit" class="btn btn-primary">
                                  {{ __('Login') }}
                              </button>

                              @if (Route::has('password.request'))
                                  <a class="btn btn-link" href="{{ route('password.request') }}">
                                      {{ __('Forgot Your Password?') }}
                                  </a>
                              @endif
                          </div>
                      </div>
                  </form>
              </div> --}}

                    {{-- <p class="text-center">
                  <span>New on our platform?</span>
                  <a href="auth-register-cover.html">
                      <span>Create an account</span>
                  </a>
              </p> --}}
                </div>
            </div>
            <!-- /Login -->
        </div>
    </div>
@endsection
