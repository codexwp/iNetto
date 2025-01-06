@extends("admin.layouts.auth")
@section('title', 'Reset Seller Password')

@section('content')

    <!-- Register -->
    <div class="card">
        <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center">
                <a href="{{ route('guest.index') }}" class="app-brand-link gap-2">
                    @if(config('settings.system_general.logo_path'))
                        <img class="w-100" src="{{ asset('storage/' . config('settings.system_general.logo_path')) }}">
                    @else
                        <i class='bx bx-station' style="font-size: 35px;"></i>
                        <span class="app-brand-text demo menu-text fw-bold ms-2">{{ config('settings.system_general.logo_text', 'iNetto') }}</span>
                    @endif

                </a>
            </div>
            <!-- /Logo -->
            <h5 class="mb-5 text-center">Reset Seller Password</h5> <br>

            <form id="formAuthentication" class="mb-3" action="{{ route('seller.password.email') }}" method="post">
                @csrf

                <div class="mb-10">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Your email address" autofocus="" value="{{ old('email') }}" autocomplete="off">
                    @error('email')
                    <span class="form-text text-danger" style="color: red;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-5">
                    <button class="btn btn-primary d-grid w-100" type="submit">{{ __('Send Password Reset Link') }}</button>
                </div>
            </form>

            <p class="text-center">
                <a href="{{ route('seller.login') }}">
                    <span>Back to Login</span>
                </a>
            </p>

        </div>
    </div>
    <!-- /Register -->

@endsection
