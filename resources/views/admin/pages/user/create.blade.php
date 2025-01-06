@extends('admin.layouts.app')
@section('title', 'Create User')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <form method="post" action="{{ route('admin.user.create') }}">
                @csrf
                <div class="card mb-6">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Create User</h5>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-sm-8">
                                <div class="row mb-6">
                                    <label class="col-sm-3 col-form-label required" for="name">Name</label>
                                    <div class="col-sm-9">
                                        <input name="name" type="text" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Person/company name" required>
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
                                    <label class="col-sm-3 col-form-label" for="mobile">Mobile</label>
                                    <div class="col-sm-9">
                                        <input name="mobile" type="tel" value="{{ old('mobile') }}" class="form-control @error('mobile') is-invalid @enderror" id="mobile" placeholder="Mobile number (without country code)">
                                        @error('mobile')
                                        <div class="form-text text-danger"> {{ $message }} </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <label class="col-sm-3 col-form-label required" for="seller_id">Seller</label>
                                    <div class="col-sm-9">
                                        <select name="seller_id" id="seller_id" class="form-select @error('seller_id') is-invalid @enderror" required>
                                            @foreach($sellers as $v)
                                                <option value="{{ $v->id }}" {{ old('seller_id') == $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('seller_id')
                                        <div class="form-text text-danger"> {{ $message }} </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <label class="col-sm-3 col-form-label required" for="username">Username</label>
                                    <div class="col-sm-9">
                                        <input gid="{{ \App\Models\User::generateUsername() }}" oid="{{ old('username') }}" name="username" type="text" value="" class="form-control @error('username') is-invalid @enderror" id="username" placeholder="Username/PPPoe name" required>
                                        <div class="form-text"> username for pppoe and login. </div>
                                        @error('username')
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
                                        <div class="form-text">password for pppoe and login. </div>
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
                                    <label class="col-sm-3 col-form-label required" for="package_id">Package</label>
                                    <div class="col-sm-9">
                                        <select old_id="{{old('package_id')}}" name="package_id" id="package_id" class="form-select @error('package_id') is-invalid @enderror" required>
                                        </select>
                                        @error('package_id')
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
                                    <label class="col-sm-3 col-form-label" for="division">Address</label>
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
                                <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary">Back</a>
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
            const seller_element = $("select[name='seller_id']");
            const package_element = $("select[name='package_id']");
            const username_element = $("input[name='username']");

            const old_id = package_element.attr('old_id');
            seller_id = seller_element.val();
            update_packages(package_element, seller_id, old_id ?? null)

            seller_element.change(function (){
                let seller_id = $(this).val();
                update_packages(package_element, seller_id);
                update_username(seller_id);
            })

            let oid = username_element.attr('oid');

            if(oid=='') {
                update_username(seller_id);
            }

            function update_username(seller_id)
            {
                let gid = username_element.attr('gid');
                username_element.val(seller_id + '_' + gid);
            }

            function update_packages(element, seller_id, selected_id = null)
            {
                package_element.html('<option>Loading</option>');
                axios.get(BASE_URL + '/admin/sellers/'+ seller_id +'/packages')
                    .then((response) => {
                        let data = response.data.data.packages;
                        if(data == null) { return }
                        let html = '';
                        for(let i = 0; i < data.length; i++) {
                            html +='<option value="' + data[i].id+ '">'+ data[i].name +'</option>'
                        }
                        package_element.html(html);
                        if(selected_id) {
                            package_element.val(selected_id);
                        }
                    })
                    .catch(error => {
                        console.log(error);
                        notify(error.response.data.message, 'error')
                    })
                    .finally(() => {});
            }


        })
    </script>
@endpush
