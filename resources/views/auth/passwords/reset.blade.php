@extends("layouts.auth")
@section('title', 'Reset User Password')

@section('content')

    <!-- Register -->
    <div class="card">
        <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center">
                <a href="{{ route('login') }}" class="app-brand-link gap-2">
                    @if(config('settings.system_general.logo_path'))
                        <img class="w-100" src="{{ asset('storage/' . config('settings.system_general.logo_path')) }}">
                    @else
                        <i class='bx bx-station' style="font-size: 35px;"></i>
                        <span class="app-brand-text demo menu-text fw-bold ms-2">{{ config('settings.system_general.logo_text', 'iNetto') }}</span>
                    @endif

                </a>
            </div>
            <!-- /Logo -->
            <h5 class="mb-5 text-center">Reset User Password</h5> <br>

            <form id="formAuthentication" class="mb-3" action="{{ route('admin.password.update') }}" method="post">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-6">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Your email address" autofocus="" value="{{ $email ?? old('email') }}" autocomplete="off">
                    @error('email')
                    <span class="form-text text-danger" style="color: red;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                    @error('password')
                    <span class="form-text text-danger" style="color: red;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-10">
                    <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                </div>

                <div class="mb-6">
                    <button class="btn btn-primary d-grid w-100" type="submit">{{ __('Reset Password') }}</button>
                </div>

            </form>

            <p class="text-center">
                <a href="{{ route('login') }}">
                    <span>Back to Login</span>
                </a>
            </p>

        </div>
    </div>
    <!-- /Register -->

@endsection
