@extends('admin.layouts.app')
@section('title', 'Sellers List')

@section('content')
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-sm-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Sellers</h5>
                    <a href="{{ route('admin.seller.create') }}" class="btn btn-sm btn-outline-primary float-end">Add New</a>
                </div>
                <div class="card-body pb-2 text-center">
                    <form class="d-none d-sm-block" id="filterForm" method="get">
                        <div class="row">
                            <div class="col-sm-1 mb-3">
                                <input name="id" type="text" value="{{ request('id')}}" class="form-control" placeholder="ID">
                            </div>
                            <div class="col-sm-3 mb-3">
                                <input name="q" type="text" value="{{ request('q')}}" class="form-control" placeholder="Name/Email/Mobile">
                            </div>

                            <div class="col-sm-2 mb-3">
                                <select name="tariff_id" class="form-select">
                                    <option value="">Tariff</option>
                                    @foreach($tariffs as $v)
                                        <option value="{{ $v->id }}" {{ request('tariff_id') == $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                                    @endforeach
                                </select>
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
                                <th>Name</th>
                                <th class="text-center">Balance({{config('settings.system_general.currency_symbol', '$')}})</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Tariff</th>
                                <th class="text-center">Users</th>
                            </tr>
                            </thead>
                            <tbody class="table-border-bottom-0 table-body">
                            @foreach($sellers as $seller)
                                <tr>
                                    <td class="text-center">{{ $seller->id }}</td>
                                    <td class="cursor-pointer text-primary detail-link text-decoration-underline"><a href="{{ route('admin.seller.detail', $seller->id) }}">{{ $seller->name }}</a></td>
                                    <td class="text-end">{{ $seller->balance ?? 0 }}</td>
                                    <td>{{ $seller->email }}</td>
                                    <td>{{ $seller->mobile }}</td>
                                    <td>{{ $seller->tariff?->name }}</td>
                                    <td class="text-end">{{ $seller->users()->count() }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer mt-3">
                    {{ $sellers->links('admin.layouts.parts.paginate') }}
                </div>

            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script type="application/javascript">
        $(document).ready(function (){

        })
    </script>
@endpush
