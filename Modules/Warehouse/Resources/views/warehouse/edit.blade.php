@extends('dashboard::layouts.master')
@section('dashboardTitle',trans('warehouse::warehouses.edit warehouse'))
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <x-dashboard::breadcrumb :breadcrumb-name="trans('warehouse::warehouses.edit warehouse')"></x-dashboard::breadcrumb>

        <div class="row">
            <!-- Categories -->
            @include('dashboard::partials.sections.categories')
            <!-- /Categories -->

            <!-- Warehouse Edit -->
            <div class="col-xl-9 col-lg-8 col-md-8">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="card-header header-elements">
                            <h4 class="d-flex align-items-center">
                                    <span class="badge bg-label-secondary p-2 rounded me-3">
                                      <i class="bx bx-cube bx-sm"></i>
                                    </span>
                                @lang('warehouse::warehouses.edit warehouse')
                            </h4>

                            <div class="card-header-elements ms-auto">
                                <a class="btn btn-outline-secondary" href="{{ route('dashboard.warehouses.index') }}" type="button">
                                    @lang('warehouse::warehouses.warehouse list')
                                </a>
                            </div>
                        </div>
                        @include('dashboard::partials.alert')
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('dashboard.warehouses.update',$warehouse) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label" for="name">@lang('warehouse::warehouses.warehouse name')</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $warehouse->name }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="phone">@lang('warehouse::warehouses.warehouse phone')</label>
                                <input type="text" class="form-control" maxlength="11" id="phone" name="phone" value="{{ $warehouse->phone }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="address">@lang('warehouse::warehouses.warehouse address')</label>
                                <input type="text" class="form-control" id="address" name="address" value="{{ $warehouse->address }}">
                            </div>
                            <div class="mb-3">
                                <div class="text-light small fw-semibold mb-3">@lang('warehouse::warehouses.warehouse status')</div>
                                <label class="switch">
                                    <input type="checkbox" class="switch-input" name="status" @if($warehouse->status=='active') checked @endif >
                                    <span class="switch-toggle-slider">
                                                          <span class="switch-on">
                                                            <i class="bx bx-check"></i>
                                                          </span>
                                                          <span class="switch-off">
                                                            <i class="bx bx-x"></i>
                                                          </span>
                                                        </span>
                                    <span class="switch-label">@lang('dashboard::common.enable') / @lang('dashboard::common.disable')</span>
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary">@lang('warehouse::warehouses.update warehouse')</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /Warehouse Edit -->
        </div>
    </div>
@endsection


