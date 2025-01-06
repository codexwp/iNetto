@extends('layouts.app')
@section('title', 'Packages List')

@section('content')
    <div class="row">
        <!-- Basic Layout -->

        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Available Packages</h5>
                </div>
                <div class="card-body text-center">
                    <div class="row">
                        @foreach($packages as $package)
                            <div class="col-md-4 my-3">
                                <div class="card"  style="box-shadow: none; border: 1px solid #80808091">
                                    <div class="card-header fw-bold text-center" style="background: aliceblue;">{{ $package['name'] }}</div>
                                    <div class="card-body text-center">
                                        <p class="card-text">Validity : {{ $package['valid'] }}</p><hr>
                                        <p class="card-text">Price : {{ $package['price'] }} {{ config('settings.system_general.currency_symbol', '$') }}</p>
                                    </div>
                                    <div class="card-footer">
                                        @if($user->package_id === $package['id'])
                                        <button class="btn btn-sm btn-outline-secondary" disabled>Currently Active</button>
                                        @else
                                            <a href="{{ route('payment.bill_pay', $package['id']) }}" class="btn btn-sm btn-outline-primary">Change</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
