@extends('dashboard::layouts.master')
@section('dashboardTitle',trans('dashboard::common.roles permissions'))
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/toastr/toastr.css') }}">
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <x-dashboard::breadcrumb :breadcrumb-name="trans('dashboard::common.roles permissions')"></x-dashboard::breadcrumb>

        <div class="row">
            <!-- Categories -->
            @include('dashboard::partials.sections.categories')
            <!-- /Categories -->

            <div class="col-xl-9 col-lg-8 col-md-8">
                @livewire('role::role-list')
            </div>


        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/panel/vendor/libs/toastr/toastr.js') }}"></script>
    <script src="{{ asset('assets/panel/js/add-with-offCanvas.js') }}"></script>
@endsection

