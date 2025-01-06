@extends('layouts.app')
@section('title', 'Bill Payment')

@push('styles')
    <style>
        .gateways img{
            width: 100%;
            cursor: pointer;
            transition: transform 1s;
            object-fit: cover;
            height: 50px;
            border: 1px solid gainsboro;
            border-radius: 7px;
            padding: 5px;
        }
        .gateways label{
            overflow: hidden;
            position: relative;
        }
        .imgbgchk:checked + label>.tick_container{
            opacity: 1;
        }

        .imgbgchk:checked + label>img{
            transform: scale(1.25);
            opacity: 0.3;
        }
        .tick_container {
            transition: .5s ease;
            opacity: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            cursor: pointer;
            text-align: center;
        }
        .tick {
            background-color: #4CAF50;
            color: white;
            font-size: 18px;
            height: 30px;
            width: 30px;
            border-radius: 100%;
        }
    </style>
@endpush

@section('content')
    <bill-pay-component
        :package
        :user='@json($user)'
        :my-package='@json($package)'
        :gateways='@json($gateways)'
        :new-package='@json($new_package?? '')'
        :duration='@json($duration)'
        :currency="'{{ config('settings.system_general.currency_symbol', '$') }}'"
        :offline-message='@json($offline_message)'
    ></bill-pay-component>
@endsection
