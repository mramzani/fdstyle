@extends('dashboard::layouts.master')
@section('dashboardTitle',__('dashboard::guarantees.edit guarantee'))
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
                                @lang('dashboard::guarantees.edit guarantee')
                            </h4>

                            <div class="card-header-elements ms-auto">
                                <a class="btn btn-outline-secondary" href="{{ route('dashboard.guarantees.index') }}" type="button">
                                    @lang('dashboard::guarantees.guarantees list')
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
                        <form method="POST" action="{{ route('dashboard.guarantees.update',$guarantee) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label" for="title">@lang('dashboard::guarantees.guarantee title')</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ $guarantee->title }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="description">@lang('dashboard::guarantees.guarantee description')</label>
                                <input type="text" class="form-control" id="description" name="description" value="{{ $guarantee->description }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="link">@lang('dashboard::guarantees.guarantee link')</label>
                                <input type="text" class="form-control" id="link" name="link" value="{{ $guarantee->link }}">
                            </div>

                            <button type="submit" class="btn btn-primary">@lang('dashboard::guarantees.edit guarantee')</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /Warehouse Edit -->
        </div>
    </div>
@endsection


