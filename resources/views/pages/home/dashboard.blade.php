@extends('layouts.app')
@section('title', 'Account')


@section('content')

    <div class="row">
        <div class="col-md-6 mb-6">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Account</h5></div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody class="table-border-bottom-0 table-body">
                            @php
                                if($user->expire_at){
                                    $class = \Carbon\Carbon::createFromTimeString($user->expire_at . ' 11:59:59')->lessThan(now()) ? 'text-danger' : 'text-primary';
                                } else {
                                    $class = $user->grace_at ? 'text-warning':'text-black';
                                }
                            @endphp

                            <tr>
                                <td width="50%" class="fw-bold text-end">Customer</td><td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td width="50%" class="fw-bold text-end">Username</td><td>{{ $user->username }}</td>
                            </tr>

                            <tr>
                                <td width="50%" class="fw-bold text-end">Package</td><td>{{ $user->package?->name }}</td>
                            </tr>

                            <tr>
                                @php $package = $user->package; @endphp
                                <td width="50%" class="fw-bold text-end">Bill Amount</td><td>{{ config('settings.system_general.currency_symbol', '$') }} {{ $package? $package->price .' / '.$package->valid : 'NA' }}</td>
                            </tr>

                            <tr>
                                <td width="50%" class="fw-bold text-end">Expire At</td><td class="{{ $class }}">{{ $user->expire_at??'NA' }}</td>
                            </tr>

                            <tr>
                                <td width="50%" class="fw-bold text-end">Status</td><td class="{{ $class }}">{{ $user->is_active_client ? 'Active' : 'Inactive' }}</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection


@push('styles')
    <style>
        .card-header {padding-top: 6px !important;padding-bottom: 6px !important;}
    </style>
@endpush
