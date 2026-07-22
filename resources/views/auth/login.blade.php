<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}"
      dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>POS | {{ __('auth.login_page') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="{{ __('auth.login_page') }}">
    <meta name="author" content="Ali Jriwah">
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!--Fonts-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" >

    <!--Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" >

    <!--Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" >

    @include('layouts.auth')


</head>

<body class="login-page bg-body-secondary">
    <div style="color:#00f;" class="dropdown">
                Select Language
                    <button class="btn btn-outline-secondary dropdown-toggle"
                            data-bs-toggle="dropdown">
                        {{ strtoupper(app()->getLocale()) }}

                    </button>
                    <ul class="dropdown-menu">

                        <li>
                            <a class="dropdown-item"
                            href="{{ route('lang.switch','en') }}">
                                English
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item"
                            href="{{ route('lang.switch','ar') }}">
                                العربية
                            </a>
                        </li>

                    </ul>
                </div>
    <div class="login-box">
        <div class="login-logo"> <a href="{{ url('/') }}"><b>POS</b> {{ __('auth.system') }}</a> </div>

        <div class="card">
            <div class="card-body login-card-body">

                <p class="login-box-msg">{{ __('auth.sign_in_message') }}</p>

                @include('_message')
                <form action="{{ url('/login') }}" method="post">
                    {{ csrf_field() }}
                    <div class="mb-3 input-group"> <input type="email" name="email" value="{{ old('email')}}" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('auth.email') }}" required>
                        <div class="input-group-text"> <span class="bi bi-envelope"></span> </div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 input-group"> <input type="password" name="password"  class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('auth.password') }}" required>
                        <div class="input-group-text"> <span class="bi bi-lock-fill"></span> </div>
                         @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="form-check">
                                 <input class="form-check-input" type="checkbox" name="remember_me" value="" id="flexCheckDefault">
                                 <label class="form-check-label" for="flexCheckDefault">
                                    {{ __('auth.remember_me') }}
                                </label> </div>
                        </div>
                        <div class="col-4">
                            <div class="gap-2 d-grid"> <button type="submit" class="btn btn-primary">{{ __('auth.sign_in') }}</button> </div>
                        </div>
                    </div>
                </form>
                <p class="mb-1"> <a href="forgot-password.html">{{ __('auth.forgot_password') }}</a> </p>
                <p class="mb-1"> <a href="{{ route('customer.register') }}">{{ __('customer.register') }}</a> </p>
            </div>
        </div>
    </div>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

        <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

    </body>

</html>
