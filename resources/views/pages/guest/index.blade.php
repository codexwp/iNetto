@extends("layouts.public")
@section('title', 'Home')

@section('content')

    <div class="row">
        <div class="col-sm-9 mt-6">
            <div class="card">
                <div class="card-body">
                    <img class="w-100" src="{{ asset('assets/img/website/banner1.jpg') }}" loading="lazy">
                </div>
            </div>
        </div>
        <div class="col-sm-3 mt-6">
            <div class="card" style="height: 368px;">
                <div class="card-body pt-10">
                    <a href="{{ route('login') }}" class="btn btn-lg btn-outline-info w-100 my-4">User Login</a>
                    <a href="{{ route('seller.login') }}" class="btn btn-lg btn-outline-warning w-100 my-4">Seller Login</a>
                    <a href="{{ route('admin.login') }}" class="btn btn-lg btn-outline-secondary w-100 my-4">Admin Login</a>
                </div>
            </div>

        </div>
    </div>

    <div class="row">

        <div class="col-sm-9 mt-6">
            <div class="card">
                <div class="card-body text-center">
                    <div class="row">
                        @foreach($packages as $package)
                            <div class="col-md-4 my-3">
                                <div class="card"  style="box-shadow: none; border: 1px solid #80808091">
                                    <div class="card-header fw-bold text-center">{{ $package->name }}</div>
                                    <div class="card-body text-center">
                                        <p class="card-text">Speed : {{ $package->profile }}</p><hr>
                                        <p class="card-text">Validity : {{ $package->valid }}</p><hr>
                                        <p class="card-text">Price : {{ $package->price }} {{ config('settings.system_general.currency_symbol', '$') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mt-6">
            <div class="card">
                <div class="card-header fw-bold text-center"><i class='bx bx-location-plus'></i> Location</div>
                <div class="card-body">
                    <p class="card-text">Address : {{ config('settings.system_general.location', '234, St road, City, State') }}</p><hr>
                    <p class="card-text">Email : {{ config('settings.system_general.contact_email', 'info@inetto.com') }}</p><hr>
                    <p class="card-text">Mobile : {{ config('settings.system_general.contact_no', '01712345678') }}</p><hr>
                </div>
            </div>
        </div>

    </div>

@endsection
