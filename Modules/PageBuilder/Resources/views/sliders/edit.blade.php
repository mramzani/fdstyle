@extends('dashboard::layouts.master')
@section('dashboardTitle','ویرایش اسلایدر')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('dashboard::partials.alert')
        <form action="{{ route('dashboard.sliders.update',$slider->id) }}" method="post">
            @csrf
            <div class="row">
                <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label input-required" for="name">نام اسلایدر</label>
                                <input type="text" class="form-control @error('name') border-danger @enderror"
                                       value="{{ old('name',$slider->name) }}" id="name" name="name"
                                       placeholder="نام اسلایدر">
                                @error('name')
                                @include('dashboard::partials.validation-error')
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label input-required" for="key">کلید</label>
                                <input type="text" class="form-control @error('key') border-danger @enderror"
                                       value="{{ old('key',$slider->key) }}" id="key" name="key" placeholder="کلید">
                                @error('key')
                                @include('dashboard::partials.validation-error')
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="description">توضیحات</label>
                                <textarea id="description" class="form-control" name="description"
                                          placeholder="توضیحات را اینجا بنویسید">{{ $slider->description }}</textarea>
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
                                    @foreach(\Modules\PageBuilder\Entities\Slider::STATUS as $key => $value)
                                        <option value="{{ $key }}"
                                                @if($slider->status == $key) selected @endif>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary d-grid w-100 mb-3">
                                <span class="d-flex align-items-center justify-content-center text-nowrap">ذخیره و بروزرسانی</span>
                            </button>
                            <a href="{{ route('dashboard.sliders.index') }}" class="btn d-grid w-100 btn-outline-info">بازگشت به لیست اسلایدر</a>

                        </div>
                    </div>

                </div>
            </div>
        </form>
        <div class="row mt-2">
            <div class="col-12">
                <div class="card ">
                    <div class="card-header header-elements">
                        <span class="me-2">تصاویر اسلایدر</span>
                        <div class="card-header-elements ms-auto">
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addSliderItem">
                                افزودن تصویر
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
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

                        @if(count($slider->items) == 0)
                            <div class="alert alert-warning">
                                برای این اسلایدر تاکنون تصویری ایجاد نشده است.
                            </div>
                        @else
                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>ردیف</th>
                                        <th>عکس</th>
                                        <th>عنوان</th>
                                        <th>الویت</th>
                                        <th>عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                    @foreach($slider->items()->orderBy('priority','desc')->get() as $item)
                                        <tr>
                                            <td>{{ $loop->index + 1}}</td>
                                            <td class="w-25">
                                                <div class="d-flex justify-content-center w-100">
                                                    <div class="mx-2">
                                                        <img src="{{ $item->full_with_image_url }}"
                                                             style="max-width: 400px;height: auto"
                                                             alt="">
                                                    </div>
                                                   <div class="mx-2">
                                                       <img src="{{ $item->mobile_image_url }}"
                                                            style="max-width: 100px;height: auto"
                                                            alt="">
                                                   </div>
                                                </div>
                                            </td>
                                            <td>{{ $item->title }}</td>
                                            <td>
                                                {{ $item->priority }}
                                            </td>

                                            <td>
                                                <div class="d-inline-block text-nowrap">
                                                    <form
                                                        action="{{ route('dashboard.sliders.item.destroy',$item->id) }}"
                                                        method="post" id="itemDeleteForm-{{ $item->id }}">
                                                        @csrf
                                                        <button type="submit"
                                                                data-id="{{ $item->id }}"
                                                                class="btn btn-sm btn-icon delete-slider-item">
                                                            <i class="bx bx-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addSliderItem" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title secondary-font" id="modalCenterTitle">افزودن تصویر جدید</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('dashboard.sliders.item.store',$slider->id) }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="title" class="form-label">عنوان</label>
                                    <input type="text" id="title" class="form-control" name="title"
                                           placeholder="عنوان را وارد کنید">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="link" class="form-label">لینک</label>
                                    <input type="text" id="link" class="form-control" name="link"
                                           placeholder="لینک را وارد کنید">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="priority" class="form-label">الویت</label>
                                    <input type="text" id="priority" class="form-control" name="priority"
                                           placeholder="الویت را وارد کنید">
                                    <small>فقط عدد وارد کنید | عدد بزرگتر نشانه‌ی الویت بالاتر می‌باشد.</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="description" class="form-label">توضیحات</label>
                                    <textarea id="description" class="form-control" name="description"
                                              placeholder="توضیحات را اینجا بنویسید"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-center">
                                        <div class="bg-label-primary w-100">
                                            <img
                                                src="{{ asset('assets/panel/img/sample-slider/sample-slider-2880x600.jpg') }}"
                                                class="img-fluid d-flex cursor-pointer" onclick="uploadBanner('slider_lg')"
                                                id="slider_lg_src">
                                            <input type="hidden" class="form-control" name="slider_lg" id="slider_lg_url">
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <p class="mb-lg-0 text-sm">برای بارگزاری اسلایدر روی تصویر بالا کلیک کنید</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <div class="bg-label-primary w-100">
                                            <img
                                                src="{{ asset('assets/panel/img/sample-slider/sample-slider-640x480.jpg') }}"
                                                class="img-fluid d-flex cursor-pointer" onclick="uploadBanner('slider_md')"
                                                id="slider_md_src">
                                            <input type="hidden" class="form-control" name="slider_md" id="slider_md_url">
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <p class="mb-lg-0 text-sm">برای بارگزاری اسلایدر روی تصویر بالا کلیک کنید</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                بستن
                            </button>
                            <button type="submit" class="btn btn-primary">ذخیره</button>
                        </div>
                    </form>


                </div>
            </div>
        </div>

    </div>
@endsection
@section('script')

    <script type="text/javascript">
        /*document.addEventListener("DOMContentLoaded", function () {
            document.getElementById('slider_lg').addEventListener('click', (event) => {
                event.preventDefault();
                window.open('/file-manager/fm-button?leftPath=sliders', 'fm', 'width=1400,height=800');
            });
        });*/
        let inputId = '';
        function uploadBanner(inputName) {
            inputId = inputName;
            event.preventDefault();
            window.open('/file-manager/fm-button?leftPath=sliders', 'fm', 'width=1400,height=800');
        }

        // set file link
        /*function fmSetLink($url) {
            $("#slider_lg").attr("src", $url);
            let img_url = $url.replace('{{ config('filesystems.disks.liara.url') . '/sliders/' }}', '', $url);
            $("#image_url").val(img_url);
        }*/

        function fmSetLink($url) {
            $('#' + inputId + '_src').attr("src", $url);
            let img_url = $url.replace('{{ config('filesystems.disks.liara.url') . '/sliders/' }}', '', $url);

            $('#' + inputId + '_url').val(img_url);
        }

        $(".delete-slider-item").on('click', function (event) {
            event.preventDefault();
            const btn = $(this);
            let id = $(this).data("id");
            Swal.fire({
                title: "@lang('dashboard::common.Are you sure to delete?')",
                text: "@lang('dashboard::common.If you delete the information, it will be lost!')",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "@lang('dashboard::common.confirm')",
                cancelButtonText: "@lang('dashboard::common.cancel')",
                buttonsStyling: false,
            }).then(function (value) {
                if (!value.dismiss) {
                    $("#itemDeleteForm-" + id).submit();
                }
            });
        });

    </script>
@endsection
