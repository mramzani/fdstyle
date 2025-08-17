@extends('dashboard::layouts.master')
@section('dashboardTitle','مدیریت ویژگی محصول')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/select2/select2.css') }}"/>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card my-2">
                    <div class="card-header bg-primary text-white py-2">مدیریت ویژگی محصول</div>
                    @if($product->category->group()->has('attributes')->exists())
                        <div id="attributes"
                             data-attributes="{{ json_encode($product->category->group->attributes()->pluck('name')) }}"></div>
                    @endif

                    @if($product->category->group()->has('attributes')->exists())
                        <div class="card-body my-2">
                            @include('dashboard::partials.alert')
                            <form method="POST" action="{{ route('product.attribute.update',$product->id) }}">
                                @csrf
                                @method('PUT')
                                <div>
                                    <h6>ویژگی محصولات</h6>
                                    <hr>
                                    <div id="attribute_section" class="my-2">
                                        @foreach($product->attributes()->where('group_id',$product->category->group->id)->get() as $attribute)
                                            <div class="row d-flex" id="attribute-{{ $loop->index }}">
                                                <div class="col-12 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="form-label">عنوان ویژگی</label>
                                                        <input type="text" name="attributes[{{ $loop->index }}][name]"
                                                               class="form-control" value="{{ $attribute->name }}"
                                                               readonly>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="form-label">مقدار ویژگی</label>
                                                        <select name="attributes[{{ $loop->index }}][value]"
                                                                class="attribute-select form-control">
                                                            <option selected disabled>انتخاب کنید</option>
                                                            @foreach($attribute->values as $value)
                                                                <option value="{{ $value->value }}"
                                                                        @if($value->id == $attribute->pivot->value_id) selected @endif>{{ $value->value }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-4 d-inline-block" style="margin-top: 2rem">
                                                    <button type="button" class="btn btn-sm btn-warning"
                                                            onclick="document.getElementById('attribute-{{ $loop->index }}').remove()">
                                                        حذف
                                                    </button>
                                                    {{--<div class="form-check form-check-inline mt-3 mx-2">
                                                        <input class="form-check-input" type="checkbox"
                                                               @if($attribute->is_filterable) checked @endif
                                                               name="attributes[{{ $loop->index }}][filterable]" id="filterable-{{ $loop->index }}"
                                                               value="filterable">
                                                        <label class="form-check-label" for="filterable-{{ $loop->index }}">قابل
                                                            فیلتر</label>
                                                    </div>--}}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button class="btn btn-sm btn-danger" type="button" id="add_product_attribute">ویژگی
                                        جدید
                                    </button>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-info">ذخیره</button>
                                    <a href="{{ route('products.index') }}" class="btn btn-primary float-left">لغو</a>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="card-body my-2">
                            <div class="alert alert-info">
                                برای دسته‌بندی این محصول گروه ویژگی تعریف نشده است.
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/panel/vendor/libs/select2/select2.js') }}"></script>
    <script type="text/javascript">

        let changeAttributeValues = (event, id) => {
            let valueBox = $(`select[name='attributes[${id}][value]']`);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })

            $.ajax({
                type: 'POST',
                url: '{{ route('product.attribute.values') }}',
                data: JSON.stringify({
                    name: event.target.value
                }),
                success: function (data) {
                    valueBox.html(`
                            <option selected disabled>انتخاب کنید</option>
                            ${
                        data.data.map(function (item) {
                            return `<option value="${item}">${item}</option>`
                        })
                    }
                        `);

                    $('.attribute-select').select2({tags: true});
                }
            });
        }

        let createNewAttr = ({attributes, id}) => {
            return `
                    <div class="row" id="attribute-${id}">
                        <div class="col-4">
                            <div class="form-group">
                                   <label class="form-label">عنوان ویژگی</label>
                                 <select name="attributes[${id}][name]" onchange="changeAttributeValues(event, ${id});" class="attribute-select form-control">
                                    <option selected disabled>انتخاب کنید</option>
                                    ${
                attributes.map(function (item) {
                    return `<option value="${item}">${item}</option>`
                })
            }
                                 </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label class="form-label">مقدار ویژگی</label>
                                 <select name="attributes[${id}][value]" class="attribute-select form-control">
                                        <option selected disabled>انتخاب کنید</option>
                                 </select>
                            </div>
                        </div>

                        <div class="col-4 d-inline-block" style="margin-top: 2rem">
                            <button type="button" class="btn btn-sm btn-warning" onclick="document.getElementById('attribute-${id}').remove()">حذف</button>
                        </div>
                    </div>
                `
        }

        $('#add_product_attribute').click(function () {
            let attributesSection = $('#attribute_section');
            let id = attributesSection.children().length;
            let attributes = $("#attributes").data('attributes');

            attributesSection.append(
                createNewAttr({
                    attributes,
                    id
                })
            );
            $('.attribute-select').select2({tags: true});
        });

        $('.attribute-select').select2({tags: true});

    </script>
@endsection
