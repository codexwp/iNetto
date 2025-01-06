@extends('admin.layouts.app')
@section('title', 'Create Tariff')

@section('content')
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-md-6">
            <form method="post" action="{{ route('admin.tariff.create') }}">
                <div class="card mb-6">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Create Tariff</h5>
                    </div>
                    <div class="card-body">
                        @csrf
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label" for="name">Name</label>
                            <div class="col-sm-10">
                                <input name="name" type="text" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Tariff name" required>
                                @error('name')
                                <div class="form-text text-danger"> {{ $message }} </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <a href="{{ route('admin.tariff.index') }}" class="btn btn-outline-secondary">Back</a>
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
