@extends('admin.layouts.app')
@section('title', 'Create Seller')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <form method="post" action="{{ route('admin.seller.create') }}">
                @csrf
                <div class="card mb-6">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Create Seller</h5>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-sm-8">
                                <div class="row mb-6">
                                    <label class="col-sm-3 col-form-label required" for="name">Name</label>
                                    <div class="col-sm-9">
                                        <input name="name" type="text" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Seller name" required>
                                        @error('name')
                                        <div class="form-text text-danger"> {{ $message }} </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <label class="col-sm-3 col-form-label" for="email">Email</label>
                                    <div class="col-sm-9">
                                        <input name="email" type="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Email address">
                                        @error('email')
                                        <div class="form-text text-danger"> {{ $message }} </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <label class="col-sm-3 col-form-label required" for="mobile">Mobile</label>
                                    <div class="col-sm-9">
                                        <input name="mobile" type="tel" value="{{ old('mobile') }}" class="form-control @error('mobile') is-invalid @enderror" id="mobile" placeholder="Enter number (without country code)" required>
                                        @error('mobile')
                                        <div class="form-text text-danger"> {{ $message }} </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <label class="col-sm-3 col-form-label required" for="password">Password</label>
                                    <div class="col-sm-9">
                                        <div class="input-group input-group-merge">
                                            <input name="password" type="password" value="" class="form-control toggle-password-input @error('password') is-invalid @enderror" id="password" placeholder="*********">
                                            <span class="input-group-text cursor-pointer toggle-password"><i class="bx bx-hide"></i></span>
                                        </div>
                                        @error('password')
                                        <div class="form-text text-danger"> {{ $message }} </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <label class="col-sm-3 col-form-label required" for="password_confirmation">Password<small> (confirm)</small></label>
                                    <div class="col-sm-9">
                                        <div class="input-group input-group-merge">
                                            <input name="password_confirmation" type="password" value="" class="form-control toggle-password-input @error('password_confirmation') is-invalid @enderror" id="password_confirmation" placeholder="*********">
                                            <span class="input-group-text cursor-pointer toggle-password"><i class="bx bx-hide"></i></span>
                                        </div>
                                        @error('password_confirmation')
                                        <div class="form-text text-danger"> {{ $message }} </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <label class="col-sm-3 col-form-label required" for="profile">Tariff</label>
                                    <div class="col-sm-9">
                                        <select name="tariff_id" id="tariff_id" class="form-select @error('tariff_id') is-invalid @enderror" required>
                                            <option value="">Select One</option>
                                            @foreach($tariffs as $t)
                                                <option value="{{ $t->id }}" {{ old('tariff_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('tariff_id')
                                        <div class="form-text text-danger"> {{ $message }} </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <label class="col-sm-3 col-form-label" for="govt_id">Govt.ID</label>
                                    <div class="col-sm-9">
                                        <input name="govt_id" type="text" value="{{ old('govt_id') }}" class="form-control @error('govt_id') is-invalid @enderror" id="govt_id" placeholder="NID/Driver License/Passport No">
                                        @error('govt_id')
                                        <div class="form-text text-danger"> {{ $message }} </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <label class="col-sm-3 col-form-label" for="zip_code">Address</label>
                                    <div class="col-sm-9">
                                        <div class="row">
                                            <div class="col-sm-6 mb-6">
                                                <input name="zip_code" type="text" value="{{ old('zip_code') }}" class="form-control @error('zip_code') is-invalid @enderror" id="zip_code" placeholder="Zip code">
                                            </div>
                                            <div class="col-sm-6 mb-6">
                                                <input name="state" type="text" value="{{ old('state') }}" class="form-control @error('state') is-invalid @enderror" id="state" placeholder="State">
                                            </div>
                                            <div class="col-sm-6 mb-6">
                                                <input name="city" type="text" value="{{ old('city') }}" class="form-control @error('city') is-invalid @enderror" id="city" placeholder="City">
                                            </div>
                                            <div class="col-sm-6 mb-6">
                                                <input name="town" type="text" value="{{ old('town') }}" class="form-control @error('town') is-invalid @enderror" id="town" placeholder="Town/Area">
                                            </div>
                                            <div class="col-sm-12 mb-6">
                                                <input name="street" type="text" value="{{ old('street') }}" class="form-control @error('street') is-invalid @enderror" id="street" placeholder="Street/House">
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <a href="{{ route('admin.seller.index') }}" class="btn btn-outline-secondary">Back</a>
                                <button type="submit" class="btn btn-primary btn-save ms-5">Save</button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="application/javascript">
        $(document).ready(function (){

        })
    </script>
@endpush
