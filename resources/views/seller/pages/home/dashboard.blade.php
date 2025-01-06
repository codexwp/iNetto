@extends('seller.layouts.app')
@section('title', 'Seller Dashboard')

@section('content')
<div class="row">

    <div class="col-sm-3 mb-6">
        <div class="card h-100 border-left">
            <div class="card-body py-3">
                <p class="mb-1">My Balance</p>
                <h4 class="card-title mb-0">{{ config('settings.system_general.currency_symbol', '$') }} {{ $seller_balance }}</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-3 mb-6">
        <div class="card h-100 border-left">
            <div class="card-body py-3">
                <p class="mb-1">Bill Pay <small> - this month</small></p>
                <h4 class="card-title mb-0">{{ config('settings.system_general.currency_symbol', '$') }} {{ $total_bill_pay }}</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-3 mb-6">
        <div class="card h-100 border-left">
            <div class="card-body py-3">
                <p class="mb-1">Costs <small> - this month</small></p>
                <h4 class="card-title mb-0">{{ config('settings.system_general.currency_symbol', '$') }} {{ $total_seller_costs }}</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-3 mb-6">
        <div class="card h-100 border-left">
            <div class="card-body py-3">
                <p class="mb-1">Deposit <small> - this month</small></p>
                <h4 class="card-title mb-0">{{ config('settings.system_general.currency_symbol', '$') }} {{ $total_deposit }}</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-3 mb-6 d-none">
        <div class="card h-100 border-left">
            <div class="card-body py-3">
                <p class="mb-1">Withdraw <small> - this month</small></p>
                <h4 class="card-title mb-0">{{ config('settings.system_general.currency_symbol', '$') }} {{ $total_withdraw }}</h4>
            </div>
        </div>
    </div>
    <div class="col-sm-3 mb-6">
        <div class="card h-100 border-left">
            <div class="card-body py-3">
                <p class="mb-1">Total Users</p>
                <h4 class="card-title mb-0">{{ $total_users }}</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-3 mb-6">
        <div class="card h-100 border-left">
            <div class="card-body py-3">
                <p class="mb-1">Active Users</p>
                <h4 class="card-title mb-0">{{ $total_active_users }}</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-3 mb-6">
        <div class="card h-100 border-left">
            <div class="card-body py-3">
                <p class="mb-1">Inactive Users</p>
                <h4 class="card-title mb-0">{{ $total_inactive_users }}</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-3 mb-6">
        <div class="card h-100 border-left">
            <div class="card-body py-3">
                <p class="mb-1">Expired Users</p>
                <h4 class="card-title mb-0">{{ $total_expired_users }}</h4>
            </div>
        </div>
    </div>

</div>


@endsection

@push('styles')
    <style>
        .border-left {border-left: 2px solid var(--bs-danger-border-subtle);}
    </style>
@endpush
