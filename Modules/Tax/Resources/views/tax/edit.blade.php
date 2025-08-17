@extends('dashboard::layouts.master')
@section('dashboardTitle',__('tax::taxes.edit tax'))
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('dashboard::partials.breadcrumb')

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
                                @lang('tax::taxes.edit tax')
                            </h4>

                            <div class="card-header-elements ms-auto">
                                <a class="btn btn-outline-secondary" href="{{ route('dashboard.taxes.index') }}" type="button">
                                    @lang('tax::taxes.tax list')
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
                        <form method="POST" action="{{ route('dashboard.taxes.update',$tax) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label" for="name">@lang('tax::taxes.tax title')</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $tax->name }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="rate">@lang('tax::taxes.tax percent')</label>
                                <input type="text" class="form-control" id="rate" name="rate" value="{{ $tax->rate }}">
                            </div>

                            <button type="submit" class="btn btn-primary">@lang('tax::taxes.edit tax')</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /Warehouse Edit -->
        </div>
    </div>
@endsection


