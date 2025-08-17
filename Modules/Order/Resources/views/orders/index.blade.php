@extends('dashboard::layouts.master')
@section('dashboardTitle','مدیریت سفارشات آنلاین')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/flatpickr/flatpickr.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/toastr/toastr.css') }}">
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-xl-12">
                <h6 class="text-muted">لیست سفارشات آنلاین</h6>
                @include('dashboard::partials.alert')
                <livewire:order::orders.order-list />
            </div>
        </div>
    </div>
@endsection
