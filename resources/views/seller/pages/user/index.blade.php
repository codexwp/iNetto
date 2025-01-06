@extends('seller.layouts.app')
@section('title', 'Users List')

@section('content')
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-sm-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Users</h5>
                    <div>
                        <button id="btnSearch" type="button" class="btn btn-sm btn-primary">Search</button>
                        <a href="{{ route('seller.user.index') }}" class="btn btn-sm btn-outline-secondary ms-5">Clear</a>
                        <a href="{{ route('seller.user.create') }}" class="btn btn-sm btn-outline-primary  ms-5">Add New</a>
                    </div>

                </div>
                <div class="card-body pb-2">
                    <form id="filterForm" method="get">
                        <div class="row">
                        <div class="col-sm-1 mb-3">
                            <input name="id" type="text" value="{{ request('id')}}" class="form-control" placeholder="ID">
                        </div>
                        <div class="col-sm-3 mb-3">
                            <input name="q" type="text" value="{{ request('q')}}" class="form-control" placeholder="Name/Username/Mobile">
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
                            <select name="is_active_client" class="form-select">
                                <option value="">All Status</option>
                                <option value="1" {{ request('is_active_client') == '1' ? 'selected' : '' }}>Enabled</option>
                                <option value="0" {{ request('is_active_client') == '0' ? 'selected' : '' }}>Disabled</option>
                            </select>
                        </div>
                        <div class="col-sm-2 mb-3">
                            <select name="is_expired" class="form-select">
                                <option value="">All Validity</option>
                                <option value="2" {{ request('is_expired') == '2' ? 'selected' : '' }}>Not Expired</option>
                                <option value="1" {{ request('is_expired') == '1' ? 'selected' : '' }}>ALL Expired</option>
                                <option value="3" {{ request('is_expired') == '3' ? 'selected' : '' }}>Expire in Today</option>
                                <option value="4" {{ request('is_expired') == '4' ? 'selected' : '' }}>Expire in Tomorrow</option>
                                <option value="5" {{ request('is_expired') == '5' ? 'selected' : '' }}>Expire in 3 Days</option>
                                <option value="6" {{ request('is_expired') == '6' ? 'selected' : '' }}>Expire in 5 Days</option>
                            </select>
                        </div>

                    </div>
                    </form>
                </div>
            </div>
            <div class="card mb-6">
                <div class="card-body">
                    <form action="{{ route('seller.payment.bulk_payment') }}" method="post" class="text-center">
                        @csrf
                        <input type="hidden" name="user_packages">
                        <button id="btnBulkPayment" type="submit" class="btn btn-sm btn-primary mb-3 d-none"><i class="bx bx-dollar-circle me-1 lh-1"></i> Bulk Payment</button>
                    </form>
                    <div class="table-responsive text-nowrap table-fixed-header">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th><input id="checkAll" class="form-check-input" type="checkbox" value="1"></th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Mobile</th>
                                <th>Package</th>
                                <th>Status</th>
                                <th>Expire</th>
                            </tr>
                            </thead>
                            <tbody class="table-border-bottom-0 table-body">
                            @forelse($users as $user)
                                @php
                                    if($user->expire_at){
                                            $class = \Carbon\Carbon::createFromTimeString($user->expire_at . ' 11:59:59')->lessThan(now()) ? 'text-danger' : 'text-primary';
                                        } else {
                                            $class = $user->grace_at ? 'text-warning':'text-black';
                                        }

                                @endphp
                                <tr>
                                    <td><input class="form-check-input check-single" type="checkbox" value="{{ $user->id }}" pid="{{ $user->package_id }}"></td>
                                    <td>{{ $user->id }}</td>
                                    <td><a class="{{ $class }} text-decoration-underline" href="{{ route('seller.user.detail', $user->id) }}">{{ $user->name }}</a></td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->mobile }}</td>
                                    <td>{{ $user->package?->name }}</td>
                                    <td class="{{ $user->is_active_client ? 'text-primary' : 'text-danger' }}">{{ $user->is_active_client ? 'Enabled' : 'Disabled' }}</td>
                                    <td class="{{ $class }}">
                                        {{ $user->expire_at }}
                                        @if($user->grace_at)
                                            <i onclick="alert('Activated at : \n{{ $user->grace_at }}')" class='bx bx-error ms-2 text-warning cursor-pointer' style="font-size: 16px;vertical-align: text-bottom;"></i>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center">No records are found</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer mt-3">
                    {{ $users->links('admin.layouts.parts.paginate') }}
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="multiPayBill" tabindex="-1" aria-modal="true" role="dialog" style="padding-left: 0px;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header pb-2" style="border-bottom: 1px solid #eee;">
                    <h5 class="modal-title" id="exampleModalLabel1">Multi Pay</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col text-center">
                            <h5>You have selected <data id="checkedUserCount"></data></h5>
                            <p>Bill pay : Seller must have to have available balance</p>
                            <p>Apply grace : Account will be enabled for {{ config('settings.system_general.grace_period', 3) }} days without money.</p>
                            <p class="text-danger">It will take few moments, do not close browser.</p>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-center">
                    <button id="applyGrace" type="button" class="btn btn-outline-secondary mx-3">Apply Grace</button>
                    <button id="payBill" type="button" class="btn btn-primary mx-3">Bill Pay</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script type="application/javascript">
        $(document).ready(function (){
            $("#btnSearch").click(function (){
                $("form#filterForm").submit();
            });

            let user_packages = [];
            showHidePayBillButton()

            $('#checkAll').change(function() {
                var isChecked = $(this).is(':checked');
                $('.check-single').prop('checked', isChecked);
                showHidePayBillButton()
            });

            $('.check-single').change(function() {
                showHidePayBillButton()
            });

            function showHidePayBillButton() {
                const checkedCount = $('.check-single:checked').length;
                const totalCount = $('.check-single').length
                $('#checkAll').prop('checked', checkedCount == totalCount);
                if(checkedCount > 0) {
                    $("#btnBulkPayment").removeClass('d-none');
                } else {
                    $("#btnBulkPayment").addClass('d-none');
                }
                const suffix = checkedCount == 1 ? ' user' : ' users';
                $("#checkedUserCount").text(checkedCount + suffix);
                user_packages = [];
                $('.check-single:checked').each(function (i,e) {
                    user_packages.push({
                        uid : $(e).val(),
                        pid : $(e).attr('pid')
                    });
                });

                $("input[name='user_packages']").val(JSON.stringify(user_packages));
            }
        });
    </script>
@endpush
