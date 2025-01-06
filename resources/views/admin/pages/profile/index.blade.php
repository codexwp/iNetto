@extends('admin.layouts.app')
@section('title', 'My Profile')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="nav-align-top">
                <ul class="nav nav-pills flex-column flex-md-row mb-6">
                    <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="bx bx-sm bx-user me-1_5"></i> My Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.profile.change_password') }}"><i class="bx bx-sm bxs-key me-1_5"></i> Change Password</a></li>
                </ul>
            </div>
        </div>

        <div class="col-sm-12">
            <form method="post" action="{{ route('admin.profile.update') }}">
                @csrf
                <div class="card mb-6">
                    <div class="card-body mt-10">
                        <div class="row justify-content-center">
                            <div class="col-sm-6">
                                <div class="row mb-6">
                                    <label class="col-sm-3 col-form-label required" for="name">Name</label>
                                    <div class="col-sm-9">
                                        <input name="name" type="text" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Seller name" required>
                                        @error('name')
                                        <div class="form-text text-danger"> {{ $message }} </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <label class="col-sm-3 col-form-label required" for="email">Email</label>
                                    <div class="col-sm-9">
                                        <input name="email" type="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Email address" required>
                                        @error('email')
                                        <div class="form-text text-danger"> {{ $message }} </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <label class="col-sm-3 col-form-label required" for="mobile">Mobile</label>
                                    <div class="col-sm-9">
                                        <input name="mobile" type="tel" value="{{ old('mobile', $user->mobile) }}" class="form-control @error('mobile') is-invalid @enderror" id="mobile" placeholder="Enter number (without country code)" required>
                                        @error('mobile')
                                        <div class="form-text text-danger"> {{ $message }} </div>
                                        @enderror
                                    </div>
                                </div>


                            </div>

                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <button type="submit" class="btn btn-primary btn-save ms-5">Update</button>
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
