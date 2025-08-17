@extends('dashboard::layouts.master')
@section('dashboardTitle',config('dashboard.name'))
@section('styles')
    <link rel="stylesheet" href="{{asset('assets/panel/vendor/libs/quill/typography.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/panel/vendor/libs/quill/editor.css')}}"/>
    <script src="{{ asset('assets/panel/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/quill/quill.js') }}"></script>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <x-dashboard::breadcrumb :breadcrumb-name="config('dashboard.name')"></x-dashboard::breadcrumb>
        <div class="row">
            <!-- Categories -->
            @include('dashboard::partials.sections.categories')
            <!-- /Categories -->

            <!-- Warehouse list -->
            <div class="col-xl-9 col-lg-8 col-md-8">
                @include('dashboard::partials.alert')
                <div class="card overflow-hidden">

                    <div class="card-body">
                        <form action="{{ route('dashboard.company.update') }}" id="formAccountSettings" method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <div class="mb-3 col-md-12">
                                    <label for="siteTitle"
                                           class="form-label">@lang('dashboard::company.site_title')</label>
                                    <input class="form-control" type="text" id="siteTitle" name="site_title"
                                           value="{{ $company->site_title }}" autofocus>
                                    @error('site_title')
                                    <x-dashboard::validation-error :message="$message"></x-dashboard::validation-error>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-12">
                                    <label for="desc" class="form-label">@lang('dashboard::company.desc')</label>
                                    <div id="aboutUs">{!! $company->desc !!}</div>
                                    <textarea class="d-none" id="desc" name="desc">{{ $company->desc }}</textarea>
                                    @error('desc')
                                    <x-dashboard::validation-error :message="$message"></x-dashboard::validation-error>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="email" class="form-label">@lang('dashboard::company.email')</label>
                                    <input class="form-control" type="text" id="email" name="email"
                                           value="{{ $company->email }}" autofocus>
                                    @error('email')
                                    <x-dashboard::validation-error :message="$message"></x-dashboard::validation-error>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="phone" class="form-label">@lang('dashboard::company.phone')</label>
                                    <input class="form-control" type="text" id="phone" name="phone"
                                           value="{{ $company->phone }}" autofocus>
                                    @error('phone')
                                    <x-dashboard::validation-error :message="$message"></x-dashboard::validation-error>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-12">
                                    <label for="address" class="form-label">@lang('dashboard::company.address')</label>
                                    <textarea class="form-control" id="address"
                                              name="address">{{ $company->address }}</textarea>
                                    @error('address')
                                    <x-dashboard::validation-error :message="$message"></x-dashboard::validation-error>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="warehouse"
                                           class="form-label">@lang('dashboard::company.warehouse')</label>
                                    <select class="form-control" name="warehouse_id" id="warehouse">
                                        <option value="">انبار پیشفرض را انتخاب کنید</option>
                                        @foreach(\Modules\Warehouse\Entities\Warehouse::all()->pluck('name','id') as $key => $value)
                                            <option value="{{ $key }}"
                                                    @if($key == $company->warehouse->id) selected="selected" @endif >{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('warehouse_id')
                                    <x-dashboard::validation-error :message="$message"></x-dashboard::validation-error>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="unit" class="form-label">@lang('dashboard::company.unit')</label>
                                    <select class="form-control" name="unit_id" id="unit">
                                        <option value="">واحدشمارش پیشفرض را انتخاب کنید</option>
                                        @foreach(\Modules\Unit\Entities\Unit::all()->pluck('name','id') as $key => $value)
                                            <option value="{{ $key }}"
                                                    @if($key == $company->unit->id) selected="selected" @endif >{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('unit_id')
                                    <x-dashboard::validation-error :message="$message"></x-dashboard::validation-error>
                                    @enderror
                                </div>
                                <div class="col-12 mb-2">
                                    <label class="form-label w-100" for="logo">@lang('dashboard::company.logo')</label>
                                    <div class="input-group input-group-merge">
                                        <input id="logo" name="image" class="form-control" type="file">
                                        <input type="hidden" value="company" name="folder">
                                    </div>


                                    @if($company->logo)
                                        <div class="mt-3 d-flex justify-content-start align-items-start">
                                            <div class="avatar avatar-xl mt-3">
                                                <img class="rounded" id="currentImg" alt="current image"
                                                     src="{{ $company->image_url }}">
                                                <input type="hidden" name="old_image" value="{{ $company->logo }}">
                                            </div>
                                            <button type="button" id="deleteCurrentImgBtn"
                                                    class="btn btn-outline-danger btn-sm m-2">
                                                @lang('dashboard::common.delete')
                                            </button>

                                            @error('logo')
                                            <x-dashboard::validation-error
                                                message="{{ $message }}"></x-dashboard::validation-error>
                                            @enderror
                                        </div>
                                    @endif

                                </div>

                            </div>
                            <div class="mt-2">
                                @can('companies_edit')
                                    <button type="submit"
                                            class="btn btn-primary me-2">@lang('dashboard::common.save')</button>
                                @endcan
                                <a href="#" class="btn btn-label-secondary">@lang('dashboard::common.cancel')</a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            <!-- /Warehouse list -->
        </div>
    </div>
@endsection
@section('script')
    <script type="application/javascript">
        const fullToolbar = [
            ['bold', 'strike'],
            [
                {
                    color: []
                },
                {
                    background: []
                }
            ],

            [
                {
                    header: '1'
                },
                {
                    header: '2'
                },
            ],
            [
                {
                    list: 'ordered'
                },
                {
                    list: 'bullet'
                },
                {
                    indent: '-1'
                },
                {
                    indent: '+1'
                }
            ],
            [
                'direction',
                {
                    align: []
                }
            ],
            ['link', 'image'],
        ];
        const aboutUs = new Quill('#aboutUs', {
            bounds: '#aboutUs',
            placeholder: null,
            modules: {
                toolbar: fullToolbar
            },
            theme: 'snow'
        });
        $("#formAccountSettings").click(function (event) {
            //event.preventDefault();
            let desc = $("textarea[name='desc']");
            desc.html(aboutUs.root.innerHTML);

        });
        $('#deleteCurrentImgBtn').on('click', function () {
            $('input[name="old_image"]').remove();
            $("#currentImg").remove();
            this.remove();
        });
    </script>
@endsection
