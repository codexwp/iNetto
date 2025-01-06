@extends('seller.layouts.app')
@section('title', 'Bulk Payment')

@section('content')
    <bulk-payment-component2
        :user-packages='@json($user_packages)'
        :currency="'{{ config('settings.system_general.currency_symbol', '$') }}'"
    ></bulk-payment-component2>
@endsection
