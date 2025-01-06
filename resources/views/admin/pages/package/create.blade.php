@extends('admin.layouts.app')
@section('title', 'Create Package')

@section('content')
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-md-6">
            <form method="post" action="{{ route('admin.package.create') }}">
                <div class="card mb-6">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Create Package</h5>
                    </div>
                    <div class="card-body">
                            @csrf
                            <div class="row mb-6">
                                <label class="col-sm-2 col-form-label required" for="name">Name</label>
                                <div class="col-sm-10">
                                    <input name="name" type="text" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Package name" required>
                                    @error('name')
                                    <div class="form-text text-danger"> {{ $message }} </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-sm-2 col-form-label required" for="profile">Profile</label>
                                <div class="col-sm-10">
                                    <select name="profile" id="profile" class="form-select @error('profile') is-invalid @enderror" required>
                                        <option>Select One</option>
                                        @foreach($profiles as $p)
                                            @if($p->is_active)
                                                <option value="{{ $p->name }}" {{ old('profile') == $p->name ? 'selected' : '' }}>{{ $p->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('profile')
                                    <div class="form-text text-danger"> {{ $message }} </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-sm-2 col-form-label required" for="name">Validity</label>
                                <div class="col-sm-10">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <select name="validity" id="validity" class="form-select @error('validity') is-invalid @enderror" required>
                                                @for($i = 1; $i <= 31; $i++)
                                                    <option value="{{ $i }}" {{ old('validity') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <select name="validity_unit" id="validity_unit" class="form-select @error('validity_unit') is-invalid @enderror" required>
                                                @foreach(\App\Models\Package::V_UNIT_LIST as $unit)
                                                    <option value="{{ $unit }}" {{ old('validity_unit') == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                    @error('validity')
                                    <div class="form-text text-danger"> {{ $message }} </div>
                                    @enderror
                                    @error('validity_unit')
                                    <div class="form-text text-danger"> {{ $message }} </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-sm-2 col-form-label required" for="price">Price ({{config('settings.system_general.currency_symbol', '$')}})</label>
                                <div class="col-sm-10">
                                    <input name="price" type="number" value="{{ old('price') }}" class="form-control @error('price') is-invalid @enderror" id="price" placeholder="Price" required>
                                    @error('price')
                                    <div class="form-text text-danger"> {{ $message }} </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-sm-2 col-form-label " for="description">Description</label>
                                <div class="col-sm-10">
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" placeholder="Description (optional)" >{{ old('description') }}</textarea>
                                    @error('description')
                                    <div class="form-text text-danger"> {{ $message }} </div>
                                    @enderror
                                </div>
                            </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <a href="{{ route('admin.package.index') }}" class="btn btn-outline-secondary">Back</a>
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
