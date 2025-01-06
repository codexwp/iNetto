@extends('layouts.app')
@section('title', 'Payment Status')

@push('styles')
    <style>
        .alert-success {
            color: var(--bs-success-text-emphasis) !important;
            border-color: var(--bs-success-border-subtle) !important;
        }
    </style>
@endpush

@section('content')

    <div class="row">
        <!-- Basic Layout -->
        <div class="col-sm-12">
            <div class="card mb-6">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Payment Status</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

@endsection

