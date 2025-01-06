@extends('admin.layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="row">

    <div class="col-sm-12">
        <h5 class="mb-3">Dashboard</h5>
    </div>

    <div class="col-sm-12 mb-6">
        <div class="card mb-3">
            <div class="card-body pb-2 text-center">
                <form method="get">
                    <div class="row">
                        <div class="col-sm-3 mb-3">
                            <div class="input-group">
                                <span class="input-group-text">From</span>
                                <input id="from_date" type="text" name="start" class="form-control datepicker" value="{{ request('start') ?? $start_date }}">
                            </div>
                        </div>
                        <div class="col-sm-3 mb-3">
                            <div class="input-group">
                                <span class="input-group-text">To &nbsp;&nbsp;&nbsp;&nbsp;</span>
                                <input id="to_date"  type="text" name="end" class="form-control datepicker" value="{{ request('end') ?? $end_date }}">
                            </div>
                        </div>
                        <div class="col-sm-3 mb-3">
                            <select name="seller" class="form-select">
                                <option value="">All Seller</option>
                                @foreach($sellers as $v)
                                    <option value="{{ $v->id }}" {{ request('seller') == $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3 mb-3 text-start">
                            <button type="submit" class="btn btn-outline-primary">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-sm-3 mb-7">
        <div class="card h-100 border-left">
            <div class="card-body py-3">
                <p class="mb-1">Total Users</p>
                <h4 class="card-title mb-0">{{ number_format($total_users, 0, '.', ',')  }}</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-3 mb-7">
        <div class="card h-100 border-left">
            <div class="card-body py-3">
                <p class="mb-1">Active Users</p>
                <h4 class="card-title mb-0">{{ number_format($total_active_users, 0, '.', ',')  }}</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-3 mb-7">
        <div class="card h-100 border-left">
            <div class="card-body py-3">
                <p class="mb-1">Inactive Users</p>
                <h4 class="card-title mb-0">{{ number_format($total_inactive_users, 0, '.', ',')  }}</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-3 mb-7">
        <div class="card h-100 border-left">
            <div class="card-body py-3">
                <p class="mb-1">Expired Users</p>
                <h4 class="card-title mb-0">{{ number_format($total_expired_users, 0, '.', ',')  }}</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-3 mb-7">
        <div class="card h-100 border-left">
            <div class="card-body py-3">
                <p class="mb-1">Bill Paid <small> - by seller</small></p>
                <h4 class="card-title mb-0">{{ config('settings.system_general.currency_symbol', '$') }} {{ number_format($total_bill_paid_by_seller, 0, '.', ',')  }}</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-3 mb-7">
        <div class="card h-100 border-left">
            <div class="card-body py-3">
                <p class="mb-1">Seller Cost</p>
                <h4 class="card-title mb-0">{{ config('settings.system_general.currency_symbol', '$') }} {{ number_format($total_seller_costs, 0, '.', ',')  }}</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-3 mb-7">
        <div class="card h-100 border-left">
            <div class="card-body py-3">
                <p class="mb-1">Bill Paid <small> - by user</small></p>
                <h4 class="card-title mb-0">{{ config('settings.system_general.currency_symbol', '$') }} {{ number_format($total_bill_paid_by_user, 0, '.', ',')  }}</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-3 mb-7">
        <div class="card h-100 border-left">
            <div class="card-body py-3">
                <p class="mb-1">Commission<small> - bill paid by user</small></p>
                <h4 class="card-title mb-0">{{ config('settings.system_general.currency_symbol', '$') }} {{ number_format($total_commission, 0, '.', ',')  }}</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-3 mb-7">
        <div class="card h-100 border-left">
            <div class="card-body py-3">
                <p class="mb-1">Total Bill</p>
                <h4 class="card-title mb-0">{{ config('settings.system_general.currency_symbol', '$') }} {{ number_format(intval($total_bill_paid_by_seller+$total_bill_paid_by_user), 0, '.', ',')  }}</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-3 mb-7">
        <div class="card h-100 border-left">
            <div class="card-body py-3">
                <p class="mb-1">Seller Profit</p>
                <h4 class="card-title mb-0">{{ config('settings.system_general.currency_symbol', '$') }} {{ number_format(intval($total_bill_paid_by_seller-$total_seller_costs+$total_commission), 0, '.', ',')  }}</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-3 mb-7">
        <div class="card h-100 border-left">
            <div class="card-body py-3">
                <p class="mb-1">Admin Profit</p>
                <h4 class="card-title mb-0">{{ config('settings.system_general.currency_symbol', '$') }} {{ number_format($total_costs, 0, '.', ',')  }}</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-3 mb-7">
        <div class="card h-100 border-left">
            <div class="card-body py-3">
                <p class="mb-1">Total Deposit</p>
                <h4 class="card-title mb-0">{{ config('settings.system_general.currency_symbol', '$') }} {{ number_format($total_deposit, 0, '.', ',')  }}</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-3 mb-7">
        <div class="card h-100 border-left">
            <div class="card-body py-3">
                <p class="mb-1">Total Withdraw</p>
                <h4 class="card-title mb-0">{{ config('settings.system_general.currency_symbol', '$') }} {{ number_format($total_withdraw, 0, '.', ',')  }}</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-3 mb-7">
        <div class="card h-100 border-left">
            <div class="card-body py-3">
                <p class="mb-1">Seller Balance</p>
                <h4 class="card-title mb-0">{{ config('settings.system_general.currency_symbol', '$') }} {{ number_format($seller_balance, 0, '.', ',')  }}</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-3 mb-7">
        <div class="card h-100 border-left">
            <div class="card-body py-3">
                <p class="mb-1">Total Sellers</p>
                <h4 class="card-title mb-0">{{ number_format($total_sellers, 0, '.', ',')  }}</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-3 mb-7">
        <div class="card h-100 border-left">
            <div class="card-body py-3">
                <p class="mb-1">Total Packages</p>
                <h4 class="card-title mb-0">{{ number_format($total_packages, 0, '.', ',')  }}</h4>
            </div>
        </div>
    </div>

    <div class="col-sm-12 mb-7 mt-1">
        <p style="font-style: italic;font-weight: 100;">
            Note : Seller balance should be same as like as "deposit + commission - cost" from starting day to now only.
        </p>
    </div>

</div>
@endsection

@include('assets.date_picker')

@push('styles')
    <style>
        .border-left {border-left: 3px solid var(--bs-danger-border-subtle);}
    </style>
@endpush

@push('scripts')
    <script type="application/javascript">
        $( function() {
            $(".datepicker").datepicker({
                dateFormat: "yy-mm-dd" // Format: YYYY-MM-DD
            });
        } );
    </script>
@endpush
