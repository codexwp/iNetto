@extends('seller.layouts.app')
@section('title', 'Change Password')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="nav-align-top">
                <ul class="nav nav-pills flex-column flex-md-row mb-6">
                    <li class="nav-item"><a class="nav-link" href="{{ route('seller.profile.index') }}"><i class="bx bx-sm bx-user me-1_5"></i> My Profile</a></li>
                    <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="bx bx-sm bxs-key me-1_5"></i> Change Password</a></li>
                </ul>
            </div>
        </div>

        <div class="col-sm-12">
            <form method="post" action="{{ route('seller.profile.change_password') }}">
                @csrf
                <div class="card mb-6">
                    <div class="card-body mt-10">
                        <div class="row justify-content-center">
                            <div class="col-sm-6">
                                <div class="row mb-6">
                                    <label class="col-sm-4 col-form-label required" for="current_password">Current Password</label>
                                    <div class="col-sm-8">
                                        <input name="current_password" type="password" value="" class="form-control @error('current_password') is-invalid @enderror" id="current_password" placeholder="*************" required>
                                        @error('current_password')
                                        <div class="form-text text-danger"> {{ $message }} </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <label class="col-sm-4 col-form-label required" for="password">New Password</label>
                                    <div class="col-sm-8">
                                        <input name="password" type="password" value="" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="*************" required>
                                        @error('password')
                                        <div class="form-text text-danger"> {{ $message }} </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <label class="col-sm-4 col-form-label required" for="password_confirmation">New Password ( Confirm )</label>
                                    <div class="col-sm-8">
                                        <input name="password_confirmation" type="password" value="" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" placeholder="*************" required>
                                        @error('password_confirmation')
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
                                <button type="submit" class="btn btn-primary btn-save ms-5">Change</button>
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
