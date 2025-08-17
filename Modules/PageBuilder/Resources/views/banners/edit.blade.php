@extends('dashboard::layouts.master')
@section('dashboardTitle','ویرایش بنر')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <form action="{{ route('dashboard.banners.edit',$banner->id) }}" method="post">
            @csrf
            <div class="row">
                @include('dashboard::partials.alert')
                <div class="">
                    @if ($errors->any())
                        <div class="alert alert-warning">
                            <span>لطفا خطاهای زیر را بررسی نمایید</span>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li class="list-inline">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label input-required" for="name">نام بنر</label>
                                <input type="text" class="form-control @error('name') border-danger @enderror"
                                       value="{{ old('name',$banner->name) }}" id="name" name="name" placeholder="نام اسلایدر">
                                @error('name')
                                @include('dashboard::partials.validation-error')
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label input-required" for="key">کلید</label>
                                <input type="text" class="form-control @error('key') border-danger @enderror"
                                       value="{{ old('key',$banner->key) }}" id="key" name="key" placeholder="کلید">
                                @error('key')
                                @include('dashboard::partials.validation-error')
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">نوع بنر</label>
                                <select class="form-select @error('banner_type') border-danger @enderror" id="type"
                                        name="banner_type" onchange="changeBannerType(event)">
                                    <option value="" selected disabled>انتخاب نوع بنر</option>
                                    @foreach(\Modules\PageBuilder\Entities\Banner::BANNER_TYPE as $key => $value)
                                        <option value="{{ $key }}" @if($banner->type == $key) selected @endif >{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('banner_type')
                                    @include('dashboard::partials.validation-error')
                                @enderror
                            </div>

                            <div class="row d-none" id="banner1x">
                                <div class="col-12">
                                    <div class="d-flex justify-content-center">
                                        <div class="bg-label-primary w-100">
                                            <img src="{{ $banner->type == "banner1x" ? $banner->banner1_img_url : asset('assets/panel/img/sample-banners/sample-banner-1837x329.jpg') }}"
                                                class="img-fluid d-flex cursor-pointer" onclick="uploadBanner('banner1x_1')"
                                                id="banner1x_1_src">
                                            <input type="hidden" value="{{ $banner->type == "banner1x" ? unserialize($banner->banner_1)['img_url'] : '' }}" class="form-control" name="banner1x_1_url" id="banner1x_1_url" disabled>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center my-1">
                                        <input type="text" class="form-control" name="banner1x_1_link"
                                               value="{{ $banner->type == "banner1x" ? $banner->banner1_link : '' }}"
                                               id="banner1x_1_link" disabled
                                               placeholder="لینک مقصد بنر را اینجا کلیک کنید">
                                    </div>
                                </div>
                            </div>
                            <div class="row d-none" id="banner2x">
                                <div class="col-6">
                                    <div class="d-flex justify-content-center">
                                        <div class="bg-label-primary w-100">
                                            <img
                                                src="{{ $banner->type == "banner2x" ? $banner->banner1_img_url : asset('assets/panel/img/sample-banners/sample-banner-820x300.jpg') }}"
                                                class="img-fluid d-flex cursor-pointer" onclick="uploadBanner('banner2x_1')"
                                                id="banner2x_1_src">
                                            <input type="hidden" value="{{ $banner->type == "banner2x" ? unserialize($banner->banner_1)['img_url'] : '' }}" class="form-control" name="banner2x_1_url" id="banner2x_1_url" disabled>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center my-1">
                                        <input type="text" class="form-control"
                                               value="{{ $banner->type == "banner2x" ? $banner->banner1_link : '' }}"
                                               name="banner2x_1_link" id="banner2x_1_link" disabled
                                               placeholder="لینک مقصد بنر را اینجا کلیک کنید">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex justify-content-center">
                                        <div class="bg-label-primary w-100">
                                            <img
                                                src="{{ $banner->type == "banner2x" ? $banner->banner2_img_url : asset('assets/panel/img/sample-banners/sample-banner-820x300.jpg') }}"
                                                class="img-fluid d-flex cursor-pointer " onclick="uploadBanner('banner2x_2')"
                                                id="banner2x_2_src">
                                            <input type="hidden" value="{{ $banner->type == "banner2x" ? unserialize($banner->banner_2)['img_url'] : '' }}" class="form-control" name="banner2x_2_url" id="banner2x_2_url" disabled>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center my-1">
                                        <input type="text" class="form-control"
                                               value="{{ $banner->type == "banner2x" ? $banner->banner2_link : '' }}"
                                               name="banner2x_2_link" id="banner2x_2_link" disabled
                                               placeholder="لینک مقصد بنر را اینجا کلیک کنید">
                                    </div>
                                </div>
                            </div>
                            <div class="row d-none" id="banner4x">
                                <div class="col-3">
                                    <div class="d-flex justify-content-center">
                                        <div class="bg-label-primary w-100">
                                            <img
                                                src="{{ $banner->type == "banner4x" ? $banner->banner1_img_url : asset('assets/panel/img/sample-banners/sample-banner-400x300.jpg') }}"
                                                class="img-fluid d-flex cursor-pointer" onclick="uploadBanner('banner4x_1')"
                                                id="banner4x_1_src">
                                            <input type="hidden"
                                                   value="{{ $banner->type == "banner4x" ? unserialize($banner->banner_1)['img_url'] : '' }}"
                                                   class="form-control" name="banner4x_1_url" id="banner4x_1_url" disabled>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center my-1">
                                        <input type="text" class="form-control"
                                               value="{{ $banner->type == "banner4x" ? $banner->banner1_link : '' }}"
                                               name="banner4x_1_link" id="banner4x_1_link" disabled
                                               placeholder="لینک مقصد بنر را اینجا کلیک کنید">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="d-flex justify-content-center">
                                        <div class="bg-label-primary w-100">
                                            <img
                                                src="{{ $banner->type == "banner4x" ? $banner->banner2_img_url : asset('assets/panel/img/sample-banners/sample-banner-400x300.jpg') }}"
                                                class="img-fluid d-flex cursor-pointer"  onclick="uploadBanner('banner4x_2')"
                                                id="banner4x_2_src">
                                            <input type="hidden"
                                                   value="{{ $banner->type == "banner4x" ? unserialize($banner->banner_2)['img_url'] : '' }}"
                                                   class="form-control" name="banner4x_2_url" id="banner4x_2_url" disabled>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center my-1">
                                        <input type="text" class="form-control"
                                               value="{{ $banner->type == "banner4x" ? $banner->banner2_link : '' }}"
                                               name="banner4x_2_link" id="banner4x_2_link" disabled
                                               placeholder="لینک مقصد بنر را اینجا کلیک کنید">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="d-flex justify-content-center">
                                        <div class="bg-label-primary w-100">
                                            <img
                                                src="{{ $banner->type == "banner4x" ? $banner->banner3_img_url : asset('assets/panel/img/sample-banners/sample-banner-400x300.jpg') }}"
                                                class="img-fluid d-flex cursor-pointer" onclick="uploadBanner('banner4x_3')"
                                                id="banner4x_3_src">
                                            <input type="hidden"
                                                   value="{{ $banner->type == "banner4x" ? unserialize($banner->banner_3)['img_url'] : '' }}"
                                                   class="form-control" name="banner4x_3_url" id="banner4x_3_url" disabled>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center my-1">
                                        <input type="text" class="form-control" name="banner4x_3_link"
                                               value="{{ $banner->type == "banner4x" ? $banner->banner3_link : '' }}"
                                               id="banner4x_3_link" disabled
                                               placeholder="لینک مقصد بنر را اینجا کلیک کنید">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="d-flex justify-content-center">
                                        <div class="bg-label-primary w-100">
                                            <img
                                                src="{{ $banner->type == "banner4x" ? $banner->banner4_img_url : asset('assets/panel/img/sample-banners/sample-banner-400x300.jpg') }}"
                                                class="img-fluid d-flex cursor-pointer" onclick="uploadBanner('banner4x_4')"
                                                id="banner4x_4_src">
                                            <input type="hidden"
                                                   value="{{ $banner->type == "banner4x" ? unserialize($banner->banner_4)['img_url'] : '' }}"
                                                   class="form-control" name="banner4x_4_url" id="banner4x_4_url" disabled>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center my-1">
                                        <input type="text" class="form-control" name="banner4x_4_link"
                                               value="{{ $banner->type == "banner4x" ? $banner->banner4_link : '' }}"
                                               id="banner4x_4_link" disabled
                                               placeholder="لینک مقصد بنر را اینجا کلیک کنید">
                                    </div>
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
                                    @foreach(\Modules\PageBuilder\Entities\Banner::STATUS as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary d-grid w-100 mb-3">
                            <span
                                class="d-flex align-items-center justify-content-center text-nowrap">ذخیره و ویرایش</span>
                            </button>

                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
            let currentType = "{{ $banner->type }}";
            let bannerN = parseInt(currentType.slice(6,7));
            console.log("#" + currentType + "_" + bannerN + "_link");

            for (let i = 1; i <= bannerN; i++) {
                $('#' + currentType + '_' + i + '_url').attr('disabled',false);
                $('#' + currentType + '_' + i + '_link').attr('disabled',false);
            }

            $("#" + currentType).removeClass('d-none');

      /*      $("#" + currentType + "_" + bannerN + "_link").prop('disabled',false);
            $("#" + currentType + "_" + bannerN + "_url").prop('disabled',false);*/


        });
        let inputId = '';
        function uploadBanner(inputName) {
            inputId = inputName;
            event.preventDefault();
            window.open('/file-manager/fm-button?leftPath=banners', 'fm', 'width=1400,height=800');
        }
        function fmSetLink($url) {
            $('#' + inputId + '_src').attr("src", $url);
            let img_url = $url.replace('{{ config('filesystems.disks.liara.url') . '/banners/' }}', '', $url);

           $('#' + inputId + '_url').val(img_url);
        }


        function disableAllInput() {
            $("#banner1x :input").map(function() {
                $(this).prop('disabled',true);
            });

            $("#banner2x :input").map(function() {
                $(this).prop('disabled',true);
            });

            $("#banner4x :input").map(function() {
                $(this).prop('disabled',true);
            });

        }

        let changeBannerType = (event) => {
            let bannerType = event.target.value;
            hideAllType();
            disableAllInput();
            let bannerN = parseInt(bannerType.slice(6,7));

            for (let i = 1; i <= bannerN; i++) {
                $('#' + bannerType + '_' + i + '_url').attr('disabled',false);
                $('#' + bannerType + '_' + i + '_link').attr('disabled',false);
            }

            $('#' + bannerType).removeClass('d-none');
        }
        function hideAllType() {
            $("#banner1x").addClass('d-none');
            $("#banner2x").addClass('d-none');
            $("#banner4x").addClass('d-none');
        }
    </script>
@endsection
