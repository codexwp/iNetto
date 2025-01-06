@extends('seller.layouts.app')
@section('title', 'Packages List')

@section('content')
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-sm-12">
            <div class="card mb-6">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">My Packages</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap table-fixed-header">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Validity</th>
                                <th class="text-center">Price({{config('settings.system_general.currency_symbol', '$') }})</th>
                                <th class="text-center">Cost({{config('settings.system_general.currency_symbol', '$') }})</th>
                                <th class="text-center">Users</th>
                            </tr>
                            </thead>
                            <tbody class="table-border-bottom-0 table-body">
                            @forelse($packages as $p)
                                <tr>
                                    <td>{{ $p['name'] }}</td>
                                    <td>{{ $p['valid'] }}</td>
                                    <td class="text-end">{{ $p['price'] }}</td>
                                    <td class="text-end">{{ $p['cost'] }}</td>
                                    <td class="text-end">{{ $p['users'] }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">No records are found</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
