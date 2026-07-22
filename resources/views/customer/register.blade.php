<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>POS | {{ __('customer.register') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('layouts.auth')
</head>

<body class="login-page bg-body-secondary">
    <div class="login-box">
        <div class="login-logo"> <a href="{{ url('/') }}"><b>POS</b> {{ __('customer.register') }}</a> </div>

        <div class="card">
            <div class="card-body login-card-body">

                <p class="login-box-msg">{{ __('customer.register') }}</p>

                @include('_message')

                <form method="POST" action="{{ route('customer.register.store') }}">
                    {{ csrf_field() }}

                    <div class="mb-3 input-group"> <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control @error('first_name') is-invalid @enderror" placeholder="{{ __('customer.first_name') }}" required>
                        <div class="input-group-text"> <span class="bi bi-person"></span> </div>
                        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3 input-group"> <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control @error('last_name') is-invalid @enderror" placeholder="{{ __('customer.last_name') }}" required>
                        <div class="input-group-text"> <span class="bi bi-person"></span> </div>
                        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3 input-group"> <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('customer.email') }}">
                        <div class="input-group-text"> <span class="bi bi-envelope"></span> </div>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3 input-group"> <input type="text" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" placeholder="{{ __('customer.phone') }}" required>
                        <div class="input-group-text"> <span class="bi bi-telephone"></span> </div>
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3"> <textarea name="address" class="form-control" placeholder="{{ __('customer.address') }}">{{ old('address') }}</textarea> </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="gap-2 d-grid"> <button type="submit" class="btn btn-primary">{{ __('customer.register') }}</button> </div>
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary">{{ __('auth.sign_in') }}</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

</body>

</html>
