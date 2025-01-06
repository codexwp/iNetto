@extends('seller.layouts.app')
@section('title', 'Payment History')


@section('content')
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-sm-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Payments @if(request('type')) <small>( Total - {{ $payments['total_amount'] }} )</small> @endif</h5>
                    <div>
                        <button id="btnSearch" type="button" class="btn btn-sm btn-primary">Search</button>
                        <a href="{{ route('seller.payment.index') }}" class="btn btn-sm btn-outline-secondary ms-5">Clear</a>
                    </div>

                </div>
                <div class="card-body pb-2">
                    <form id="filterForm" method="get">
                        <div class="row">

                            <div class="col-sm-4 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text" for="from_date">From</span>
                                    <input id="from_date" name="from_date" type="text" value="{{ request('from_date', now()->startOfMonth()->format('Y-m-d'))}}" class="form-control datepicker">
                                </div>
                            </div>
                            <div class="col-sm-4 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text" for="to_date">To &nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    <input id="to_date" name="to_date" type="text" value="{{ request('to_date', now()->endOfMonth()->format('Y-m-d'))}}" class="form-control datepicker">
                                </div>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-sm-2 mb-3">
                                <select name="type" class="form-select">
                                    <option value="">All Type</option>
                                    @foreach(\App\Models\Payment::TYPE_LIST as $v)
                                        <option value="{{ $v }}" {{ request('type') == $v ? 'selected' : '' }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-2 mb-3">
                                <select name="package_id" class="form-select">
                                    <option value="">All Package</option>
                                    @foreach($packages as $v)
                                        <option value="{{ $v['id'] }}" {{ request('package_id') == $v['id'] ? 'selected' : '' }}>{{ $v['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-2 mb-3">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    @foreach(\App\Models\Payment::STATUS_LIST as $v)
                                        <option value="{{ $v }}" {{ request('status') == $v ? 'selected' : '' }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-2 mb-3">
                                <input name="trx_id" type="text" value="{{ request('trx_id')}}" class="form-control" placeholder="Trx. ID">
                            </div>

                            <div class="col-sm-2 mb-3">
                                <input name="user_id" type="text" value="{{ request('user_id')}}" class="form-control" placeholder="User ID">
                            </div>

                        </div>
                    </form>
                </div>
            </div>
            <div class="card mb-6">
                <div class="card-body">
                    <div class="table-responsive text-nowrap table-fixed-header">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>User</th>
                                <th>Package</th>
                                <th class="text-center">Amount({{ config('settings.system_general.currency_symbol', '$') }})</th>
                                <th class="text-center">Cost({{ config('settings.system_general.currency_symbol', '$')  }})</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Prev Bal({{ config('settings.system_general.currency_symbol', '$') }})</th>
                                <th class="text-center">New Bal({{ config('settings.system_general.currency_symbol', '$') }})</th>
                                <th>Note</th>
                            </tr>
                            </thead>
                            <tbody class="table-border-bottom-0 table-body">
                            @forelse($payments['records'] as $payment)
                                <tr>
                                    <td class="text-center">{{ $payment->id }}</td>
                                    <td>{{ $payment->created_at->format('Y-m-d h:i a') }}</td>
                                    <td class="{{ $payment->type == 'bill' ? 'text-warning' : ($payment->type == 'deposit' || $payment->type == 'commission' ? 'text-info' : 'text-danger')}}">{{ $payment->type }}</td>
                                    <td>{{ $payment->user?->name }}</td>
                                    <td>{{ $payment->package?->name }}</td>
                                    <td class="text-end">{{ $payment->amount }}</td>
                                    <td class="text-end">{{ $payment->type == 'bill' ? $payment->cost : '' }}</td>
                                    <td class="text-center"><span class="badge bg-label-{{ $payment->status == 'pending' ? 'primary' : ($payment->status == 'processing' || $payment->status == 'hold' ? 'warning' : ($payment->status == 'completed' ? 'success' : 'danger')) }}">{{ $payment->status }}</span></td>
                                    <td class="text-end">{{ $payment->seller_prev_bal }}</td>
                                    <td class="text-end">{{ $payment->seller_new_bal }}</td>
                                    <td>@if($payment->note)<a class="text-light" href="javascript:void(0)" onclick="alert('{{ $payment->note }}')">view</a>@endif</td>
                                </tr>
                                @empty
                                    <tr><td colspan="11" class="text-center">No records are found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer mt-3">
                    {{ $payments['records']->links('seller.layouts.parts.paginate') }}
                </div>
            </div>
        </div>

    </div>

@endsection

@include('assets.date_picker')

@push('scripts')
    <script type="application/javascript">
        $( function() {
            $(".datepicker").datepicker({
                dateFormat: "yy-mm-dd" // Format: YYYY-MM-DD
            });
        } );

        $(document).ready(function (){
            $("#btnSearch").click(function (e){
                $("form#filterForm").submit();
            });
        });
    </script>
@endpush
