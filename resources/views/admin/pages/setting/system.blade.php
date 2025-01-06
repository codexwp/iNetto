@extends('admin.layouts.app')
@section('title', 'System Setting')

@section('content')
    <system-setting-component :countries='@json(country_codes())' :time_zones='@json(time_zones())'></system-setting-component>
@endsection


@push('styles')
    <style>
        .nav-pills .nav-link {border-radius: 0;}
        .nav .nav-item {border: 1px solid gainsboro;margin-bottom: 10px;}
        .card-header {padding-top: 6px !important;padding-bottom: 6px !important;}
        .overview-card {border: 1px solid gainsboro;box-shadow: none;}
        .overview-card .title{border-bottom: 1px solid #eee;color:#2f4877;background: #e4e6e847;padding-left: 10px;}
    </style>
@endpush