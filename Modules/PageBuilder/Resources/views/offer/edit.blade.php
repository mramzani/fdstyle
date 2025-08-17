@extends('dashboard::layouts.master')
@section('dashboardTitle','ویرایش آفر')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/flatpickr/flatpickr.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/select2/select2.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/jquery-ui/jquery-ui.css') }}">
    <style>
        ul.ui-widget {
            font-family: "primary-font" !important;
        }

        .ui-menu .ui-menu-item {
            padding: 0.5rem 1rem !important;
            align-items: center !important;
            position: relative;
            display: flex !important;
        }

        .ui-menu-item .ui-menu-item-wrapper {
            padding: 0;
        }

        .ui-menu .ui-menu-item-wrapper:hover {
            color: transparent;
            background-color: transparent;
            border: none;
        }

        .ui-menu .ui-menu-item-wrapper.ui-state-active {
            color: #fff !important;
            background-color: #5a8dee !important;
            border: none;
            border-radius: 0.3125rem;
        }

    </style>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <form action="{{ route('dashboard.offer.edit',$data->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="row">
                @include('dashboard::partials.alert')
                <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label input-required" for="title">نام آفر</label>
                                <input type="text" class="form-control @error('title') border-danger @enderror"
                                       value="{{ old('title',$data->title) }}" id="title" name="title"
                                       placeholder="نام آفر به دلخواه وارد کنید">
                                @error('title')
                                @include('dashboard::partials.validation-error')
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label input-required" for="slug">اسلاگ آفر</label>
                                <input type="text" class="form-control @error('slug') border-danger @enderror"
                                       value="{{ old('slug',$data->slug) }}" id="slug" name="slug">
                                @error('slug')
                                @include('dashboard::partials.validation-error')
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="products"> لیست محصولات <small class="text-danger">(لیست محصولات قابل ویرایش نیست)</small></label>
                                <select class="form-control @error('products') border-danger @enderror"
                                        id="products" name="products[]" multiple>
                                    @foreach($data->products as $product)
                                        <option value="{{ $product->id }}"
                                                selected>{{ $product->name . '-' . $product->barcode }}</option>
                                    @endforeach
                                </select>
                                @error('products')
                                @include('dashboard::partials.validation-error')
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label input-required" for="percent">درصد تخفیف</label>
                                <input type="text" class="form-control @error('percent') border-danger @enderror"
                                       value="{{ old('percent',$data->percent) }}" id="percent" name="percent">
                                @error('percent')
                                @include('dashboard::partials.validation-error')
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label input-required" for="start_date">تاریخ شروع</label>
                                    <input type="text"
                                           class="form-control flatpickr-datetime @error('start_date') border-danger @enderror"
                                           value="{{ old('start_date',verta($data->start_date)->format('Y-n-j H:i')) }}"
                                           id="start_date" name="start_date">
                                    @error('start_at')
                                    @include('dashboard::partials.validation-error')
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label input-required" for="end_date">تاریخ پایان</label>
                                    <input type="text"
                                           class="form-control flatpickr-datetime @error('end_date') border-danger @enderror"
                                           value="{{ old('end_date',verta($data->end_date)->format('Y-n-j H:i')) }}"
                                           id="end_date" name="end_date">
                                    @error('end_date')
                                    @include('dashboard::partials.validation-error')
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="status" class="form-label">وضعیت</label>
                                <select class="form-select" id="status" name="status">
                                    @foreach(\Modules\PageBuilder\Entities\Offers::STATUS as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary d-grid w-100 mb-3">
                                <span
                                    class="d-flex align-items-center justify-content-center text-nowrap">ذخیره و ادامه</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/panel/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/jquery-ui/jquery.ui.autocomplete.html.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/jdate/jdate.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/flatpickr/flatpickr-jdate.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/flatpickr/l10n/fa-jdate.js') }}"></script>
    <script type="text/javascript">
        let flatpickrDatetime = $(".flatpickr-datetime");
        if (flatpickrDatetime) {
            flatpickrDatetime.flatpickr({
                enableTime: true,
                locale: 'fa',
                altInput: true,
                altFormat: 'Y/m/d - H:i',
                disableMobile: true,
            });
        }

        $("select#products").select2({
            tags: true,
            disabled: 'readonly',
        });


        /*$("select#products").select2({
            ajax: {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('dashboard.offers.products') }}",
                dataType: 'json',
                delay: 250,
                type: 'POST',
                data: function (params) {
                    return {
                        terms: params.term,
                    };
                },
                processResults: function (response, params) {
                    var select2Data = $.map(response.data, function (obj) {
                        return obj;
                    });
                    return {
                        results: select2Data,
                    };
                },
                cache: true
            },
            minimumInputLength: 3,
            language: {
                inputTooShort: function () {
                    return 'نام‌ یا بارکد محصول را جستجو کنید';
                }
            },
            templateResult: formatRepo,
            templateSelection: formatRepoSelection
        });

        function formatRepo(repo) {
            if (repo.loading) {
                return 'در حال جستجو...';
            }

            return $(
                '<span>' + repo.name + ' - ' + repo.barcode + '</span>');
        }

        function formatRepoSelection(repo) {
            return repo.name;
        }*/
    </script>
@endsection
