@extends("seller.layouts.auth")
@section('title', 'Seller Login')

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
            </div><hr>
            <!-- /Logo -->
            <h5 class="mb-6 text-center"><i class='bx bx-log-in' style="vertical-align: text-top;"></i> Seller Login</h5>

            <form id="formAuthentication" class="mb-3" action="{{ route('seller.login') }}" method="post">
                @csrf

                <div class="mb-3">
                    <label for="login" class="form-label">Username</label>
                    <input type="text" class="form-control @error('login') is-invalid @enderror" id="login" name="login" placeholder="username or email address" autofocus="" value="{{ old('login') }}" autocomplete="off">
                    @error('login')
                    <span class="form-text text-danger" style="color: red;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3 form-password-toggle">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-group input-group-merge">
                        <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="············" aria-describedby="password">
                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    </div>
                    @error('password')
                    <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-6">
                    <div class="form-check">
                        <input name="remember" class="form-check-input" type="checkbox" id="remember-me">
                        <label class="form-check-label" for="remember-me">
                            Remember Me
                        </label>
                    </div>
                </div>
                <div class="mb-6">
                    <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
                </div>
            </form>
            @if (Route::has('seller.password.request'))
                <p class="text-center">
                    <a href="{{ route('seller.password.request') }}">
                        <span>Forgot your password?</span>
                    </a>
                </p>
            @endif


        </div>
    </div>
    <!-- /Register -->

@endsection
