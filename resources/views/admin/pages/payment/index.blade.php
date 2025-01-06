@extends('admin.layouts.app')
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
                        <a href="{{ route('admin.payment.index') }}" class="btn btn-sm btn-outline-secondary ms-5">Clear</a>
                    </div>

                </div>
                <div class="card-body pb-2">
                    <form id="filterForm" class="d-none d-sm-block" method="get">
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
                                        <option value="{{ $v->id }}" {{ request('package_id') == $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
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
                                <select name="seller_id" class="form-select">
                                    <option value="">All Seller</option>
                                    @foreach($sellers as $v)
                                        <option value="{{ $v->id }}" {{ request('seller_id') == $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
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
                    <a id="filterBtn" class="btn btn-sm btn-outline-light mb-3 d-block d-sm-none w-px-150" style="margin: 0 auto;"> <i class='bx bx-filter' ></i> <span>Show Filter</span></a>
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
                                <th class="text-center">Type</th>
                                <th>User</th>
                                <th>Package</th>
                                <th>Seller</th>
                                <th class="text-center">Amount({{ config('settings.system_general.currency_symbol', '$')  }})</th>
                                <th class="text-center">Cost({{ config('settings.system_general.currency_symbol', '$')  }})</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Prev Bal({{ config('settings.system_general.currency_symbol', '$')  }})</th>
                                <th class="text-center">New Bal({{ config('settings.system_general.currency_symbol', '$')  }})</th>
                            </tr>
                            </thead>
                            <tbody class="table-border-bottom-0 table-body">
                            @forelse($payments['records'] as $payment)
                                <tr>
                                    <td class="text-center"><a pid="{{ $payment->id }}" class="text-decoration-underline btn-details" href="javascript:void(0)">{{ $payment->id }}</a></td>
                                    <td>{{ $payment->created_at->format('Y-m-d h:i a') }}</td>
                                    <td class="text-center {{ $payment->type == 'bill' ? 'text-warning' : ($payment->type == 'deposit' || $payment->type == 'commission' ? 'text-info' : 'text-danger')}}">{{ $payment->type }}</td>
                                    <td>{{ $payment->user?->name }}</td>
                                    <td>{{ $payment->package?->name }}</td>
                                    <td>{{ $payment->seller?->name }}</td>
                                    <td class="text-end">{{ $payment->amount }}</td>
                                    <td class="text-end">{{ $payment->type == 'bill' ? $payment->cost : '' }}</td>
                                    <td  class="text-center"><span class="badge bg-label-{{ $payment->status == 'pending' ? 'primary' : ($payment->status == 'processing' || $payment->status == 'hold' ? 'warning' : ($payment->status == 'completed' ? 'success' : 'danger')) }}">{{ $payment->status }}</span></td>
                                    <td class="text-end">{{ $payment->seller_prev_bal ?? "" }}</td>
                                    <td class="text-end">{{ $payment->seller_new_bal ?? "" }}</td>
                                </tr>
                                @empty
                                    <tr><td colspan="11" class="text-center">No records are found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer mt-3">
                    {{ $payments['records']->links('admin.layouts.parts.paginate') }}
                </div>
            </div>
        </div>

    </div>

    <form id="updatePayment" method="post" >
        <div class="modal fade" id="detailsModal" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Payment Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td>ID</td>
                                    <td id="pid"></td>
                                </tr>
                                <tr>
                                    <td>Date</td>
                                    <td id="pdate"></td>
                                </tr>
                                <tr>
                                    <td>Amount</td>
                                    <td id="pamount"></td>
                                </tr>
                                <tr>
                                    <td>Cost</td>
                                    <td id="pcost"></td>
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td id="ptype"></td>
                                </tr>
                                <tr>
                                    <td>User</td>
                                    <td id="puser"></td>
                                </tr>
                                <tr>
                                    <td>Seller</td>
                                    <td id="pseller"></td>
                                </tr>
                                <tr>
                                    <td>Package</td>
                                    <td id="ppackage"></td>
                                </tr>
                                <tr>
                                    <td>Prev. Balance</td>
                                    <td id="pseller_prev_bal"></td>
                                </tr>
                                <tr>
                                    <td>New Balance</td>
                                    <td id="pseller_new_bal"></td>
                                </tr>
                                <tr>
                                    <td>Start At</td>
                                    <td id="puser_start_at"></td>
                                </tr>
                                <tr>
                                    <td>Expire At</td>
                                    <td id="puser_expire_at"></td>
                                </tr>
                                <tr>
                                    <td>Bill Pay By</td>
                                    <td id="pbill_pay_by"></td>
                                </tr>
                                <tr>
                                    <td>Gateway</td>
                                    <td id="pgateway"></td>
                                </tr>
                                <tr>
                                    <td>Trx. ID</td>
                                    <td id="ptrx_id"></td>
                                </tr>
                                <tr>
                                    <td>Action By</td>
                                    <td id="paction_by"></td>
                                </tr>

                                <tr>
                                    <td>Status</td>
                                    <td id="pstatus">
                                        <select class="form-control" name="payment_status">
                                            @foreach(\App\Models\Payment::STATUS_LIST as $s)
                                                <option value="{{ $s }}">{{ ucfirst($s) }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Note</td>
                                    <td id="pnote">
                                        <textarea name="payment_note" class="form-control"></textarea>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary ms-5">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

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
            var selectedPaymentID = null;

            $("#btnSearch").click(function (e){
                $("form#filterForm").submit();
            });

            $(".btn-details").click(function () {
                selectedPaymentID = $(this).attr('pid');
                getPayments();
            })

            $("#updatePayment").submit(function (e) {
                e.preventDefault();
                updatePayment();
            });

            const currency = '{{ config('settings.system_general.currency_symbol', '$') }} ';
            function getPayments()
            {
                loading();
                let url = BASE_URL + '/admin/payments/' + selectedPaymentID + '/fetch';
                $("#detailsModal tr").show();
                axios.get(url)
                    .then((response) => {
                        const data = response.data.data;
                        const payment = data.payment;
                        $("#pid").text(payment.id);
                        $("#pdate").text(moment(payment.created_at).format('YYYY-MM-DD hh:mm a'))
                        $("#pamount").text(currency + payment.amount);
                        if(payment.type != 'bill') {
                            $("#puser").parents("tr").hide();
                            $("#pcost").parents("tr").hide();
                            $("#puser_start_at").parents("tr").hide();
                            $("#puser_expire_at").parents("tr").hide();
                            $("#ppackage").parents("tr").hide();
                            $("#pbill_pay_by").parents("tr").hide();
                            $("#ptrx_id").parents("tr").hide();
                        } else {
                            $("#puser_start_at").text(moment(payment.user_start_at).format('YYYY-MM-DD'));
                            $("#puser_expire_at").text(moment(payment.user_expire_at).format('YYYY-MM-DD'));
                            $("#ppackage").text(data.package);
                            $("#puser").text(data.user);
                            $("#pbill_pay_by").text(payment.bill_pay_by);
                            $("#ptrx_id").text(payment.trx_id);
                            $("#pcost").text(currency + payment.cost);
                        }
                        $("#pseller").text(data.seller);
                        $("#pseller_prev_bal").text(currency + payment.seller_prev_bal);
                        $("#pseller_new_bal").text(currency + payment.seller_new_bal);
                        $("#ptype").text(payment.type);
                        $("#pgateway").text(payment.gateway);
                        $("#paction_by").text(payment.action_by);
                        $("#pstatus select").val(payment.status);
                        $("#pnote textarea").val(payment.note);

                        $("#detailsModal").modal('show');
                    })
                    .catch(error => {
                        notify(error.response.data.message, 'error');
                    })
                    .finally(() => {
                        loading(false);
                    });
            }


            function updatePayment()
            {
                const status = $("#pstatus select").val();
                const note = $("#pnote textarea").val();
                if(status == '' || status == undefined) {return;}

                loading();
                let url = BASE_URL + '/admin/payments/' + selectedPaymentID + '/update';
                const data = {
                    'id' : selectedPaymentID,
                    'status' : status,
                    'note' : note
                }
                axios.post(url, data)
                    .then((response) => {
                        $("#detailsModal").modal('hide');
                        notify('Successfully updated', 'success');
                    })
                    .catch(error => {
                        notify(error.response.data.message, 'error');
                    })
                    .finally(() => {
                        loading(false);
                    });
            }
        });
    </script>

@endpush
