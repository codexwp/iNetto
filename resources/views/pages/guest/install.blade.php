@extends("layouts.public")
@section('title', 'Installation')

@section('content')
    @php
        $step = session()->get('step') ?? 1;
    @endphp

    <h4 class="text-center my-5"><span class="border-bottom"> INSTALLATION</span></h4>

    <!-- Installation -->
    <div class="row">
        <div class="col-sm-8 offset-sm-2">

            @if(session('success'))
                <div class="alert alert-primary" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <form onsubmit="loading()" method="post" action="{{ route('install') }}">
                @csrf
                @if($step === 1)

                    <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">1. Database Configuration</h5>
                            </div>
                            <div class="card-body py-5">
                                <div class="mb-5 row">
                                    <label for="license_key" class="col-md-2 col-form-label fs-6 fw-bold text-end">License</label>
                                    <div class="col-md-10">
                                        <input name="license_key" type="text" class="form-control" id="license_key" placeholder="Enter purchase code from evanto" required value="{{ old('license_key') }}">
                                    </div>
                                </div>
                                <hr class="my-10">
                                <div class="mb-5 row">
                                    <label for="host" class="col-md-2 col-form-label fs-6 fw-bold text-end">Hostname</label>
                                    <div class="col-md-10">
                                        <input name="host" type="text" class="form-control" id="host" placeholder="localhost" required value="{{ old('host') }}">
                                    </div>
                                </div>

                                <div class="mb-5 row">
                                    <label for="port" class="col-md-2 col-form-label fs-6 fw-bold text-end">Port</label>
                                    <div class="col-md-10">
                                        <input name="port" type="number" class="form-control" id="port" placeholder="3306" required value="{{ old('port') }}">
                                    </div>
                                </div>

                                <div class="mb-5 row">
                                    <label for="dbname" class="col-md-2 col-form-label fs-6 fw-bold text-end">Database</label>
                                    <div class="col-md-10">
                                        <input name="dbname" type="text" class="form-control" id="dbname" placeholder="Database name" required value="{{ old('dbname') }}">
                                    </div>
                                </div>

                                <div class="mb-5 row">
                                    <label for="username" class="col-md-2 col-form-label fs-6 fw-bold text-end">Username</label>
                                    <div class="col-md-10">
                                        <input name="username" type="text" class="form-control" id="username" placeholder="Username" required value="{{ old('username') }}">
                                    </div>
                                </div>

                                <div class="mb-5 row">
                                    <label for="password" class="col-md-2 col-form-label fs-6 fw-bold text-end">Password</label>
                                    <div class="col-md-10">
                                        <input name="password" type="text" class="form-control" id="password" placeholder="Password" required value="{{ old('password') }}">
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer text-center">
                                <button type="submit" class="btn btn-primary">Continue</button>
                            </div>
                        </div>

                @elseif($step === 2)

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">2. Admin Configuration</h5>
                        </div>
                        <div class="card-body py-5">

                            <div class="mb-5 row">
                                <label for="name" class="col-md-2 col-form-label fs-6 fw-bold text-end">Name</label>
                                <div class="col-md-10">
                                    <input name="name" type="text" class="form-control" id="name" placeholder="Admin name" required value="{{ old('name') }}">
                                </div>
                            </div>

                            <div class="mb-5 row">
                                <label for="email" class="col-md-2 col-form-label fs-6 fw-bold text-end">Email</label>
                                <div class="col-md-10">
                                    <input name="email" type="email" class="form-control" id="email" placeholder="Email address" required value="{{ old('email') }}">
                                </div>
                            </div>

                            <div class="mb-5 row">
                                <label for="mobile" class="col-md-2 col-form-label fs-6 fw-bold text-end">Mobile</label>
                                <div class="col-md-10">
                                    <input name="mobile" type="tel" class="form-control" id="mobile" placeholder="Mobile number" required value="{{ old('mobile') }}">
                                </div>
                            </div>

                            <div class="mb-5 row">
                                <label for="password" class="col-md-2 col-form-label fs-6 fw-bold text-end">Password</label>
                                <div class="col-md-10">
                                    <input name="admin_password" type="text" class="form-control" id="password" placeholder="Password" required value="{{ old('admin_password') }}">
                                </div>
                            </div>

                        </div>

                        <div class="card-footer text-center">
                            <button type="submit" class="btn btn-primary">Continue</button>
                        </div>
                    </div>
                @elseif($step === 3)

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">3. Installation Done</h5>
                        </div>
                        <div class="card-body py-5">

                            @php
                            $admin = \App\Models\Admin::find(1);
                            $seller = \App\Models\Seller::find(1);
                            @endphp

                            <div class="table-responsive mb-6">
                                <table class="table table-bordered">
                                    <tbody class="table-border-bottom-0 table-body">
                                    <tr style="background: aliceblue">
                                        <td class="fw-bold text-center" colspan="2">Admin Login</td>
                                    </tr>
                                    <tr>
                                        <td width="50%" class="fw-bold text-end">Login URL</td><td><a href="{{ url('/admin') }}" target="_blank">{{ url('/admin') }}</a></td>
                                    </tr>
                                    <tr>
                                        <td width="50%" class="fw-bold text-end">Email</td><td>{{ $admin->email }}</td>
                                    </tr>
                                    <tr>
                                        <td width="50%" class="fw-bold text-end">Password</td><td>********</td>
                                    </tr>

                                    <tr style="background: aliceblue">
                                        <td class="fw-bold text-center" colspan="2">Seller Login</td>
                                    </tr>
                                    <tr>
                                        <td width="50%" class="fw-bold text-end">Login URL</td><td><a href="{{ url('/seller') }}" target="_blank">{{ url('/seller') }}</a></td>
                                    </tr>
                                    <tr>
                                        <td width="50%" class="fw-bold text-end">Email</td><td>{{ $seller->email }}</td>
                                    </tr>
                                    <tr>
                                        <td width="50%" class="fw-bold text-end">Password</td><td>********</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>

                        <div class="card-footer text-center">
                            <a href="{{ url('/') }}" class="btn btn-primary">Go Home</a>
                        </div>


                    </div>

                @endif
            </form>
        </div>
    </div>
    <!-- /Installation -->

@endsection

