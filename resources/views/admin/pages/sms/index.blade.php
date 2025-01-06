@extends('admin.layouts.app')
@section('title', 'SMS History')

@section('content')
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-sm-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">SMS History</h5>
                    <div>
                        <button id="btnSearch" type="button" class="btn btn-sm btn-primary">Search</button>
                        <a href="{{ route('admin.sms.index') }}" class="btn btn-sm btn-outline-secondary ms-5">Clear</a>
                    </div>

                </div>
                <div class="card-body pb-2">
                    <form id="filterForm" method="get">
                        <div class="row">

                            <div class="col-sm-2 mb-3">
                                <input name="mobile" type="text" value="{{ request('mobile')}}" class="form-control" placeholder="Mobile">
                            </div>
                            <div class="col-sm-2 mb-3">
                                <input name="seller_id" type="text" value="{{ request('seller_id')}}" class="form-control" placeholder="Seller ID">
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
                                <th>ID</th>
                                <th>User</th>
                                <th>Seller</th>
                                <th>Mobile</th>
                                <th>Message</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody class="table-border-bottom-0 table-body">
                            @forelse($sms as $s)
                                <tr>
                                    <td>{{ $s->id }}</td>
                                    <td>{{ $s->user?->name }}</td>
                                    <td>{{ $s->seller?->name }}</td>
                                    <td>
                                        {{ $s->mobile }}
                                        @if(!$s->status)
                                            <i onclick="alert('{{ $s->reason }}')" class='bx bx-error ms-3 text-danger cursor-pointer'></i>
                                        @endif
                                    </td>
                                    <td><div class="fixed_msg_width cursor-pointer"> {{ $s->message }} </div></td>
                                    <td>{{ $s->created_at->format('Y-m-d h:i a') }}</td>
                                </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center">No records are found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer mt-3">
                    {{ $sms->links('admin.layouts.parts.paginate') }}
                </div>
            </div>
        </div>

    </div>

@endsection
@push('scripts')
    <style>
        .fixed_msg_width{

            white-space: initial;
        }
    </style>
    <script type="application/javascript">
        $(document).ready(function (){
            $("#btnSearch").click(function (){
                $("form#filterForm").submit();
            })
        });
    </script>
@endpush
