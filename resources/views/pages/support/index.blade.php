@extends('layouts.app')
@section('title', 'Support')


@section('content')

    <div class="row">
        <div class="col-md-6 mb-6">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Company Info</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody class="table-border-bottom-0 table-body">
                                <tr>
                                    <td class="w-50 fw-bold text-end">Company Name</td><td>{{ $company['title'] }}</td>
                                </tr>
                                <tr>
                                    <td class="w-50 fw-bold text-end">Mobile Number</td><td>{{ $company['contact_no'] }}</td>
                                </tr>
                                <tr>
                                    <td class="w-50 fw-bold text-end">Support Email</td><td>{{ $company['contact_email'] }}</td>
                                </tr>
                                <tr>
                                    <td class="w-50 fw-bold text-end">Office Location</td><td>{{ $company['location'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <div class="card mt-6">
                <div class="card-header"><h5 class="mb-0">Seller Info</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody class="table-border-bottom-0 table-body">
                            <tr>
                                <td class="w-50 fw-bold text-end">Name</td><td>{{ $seller->name }}</td>
                            </tr>
                            <tr>
                                <td class="w-50 fw-bold text-end">Mobile Number</td><td>{{ $seller->mobile }}</td>
                            </tr>
                            <tr>
                                <td class="w-50 fw-bold text-end">Support Email</td><td>{{ $seller->email }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection
