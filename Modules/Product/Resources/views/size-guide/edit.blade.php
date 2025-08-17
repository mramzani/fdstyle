@extends('dashboard::layouts.master')
@section('dashboardTitle','ویرایش راهنمای سایز')
@section('styles')
    <script src="https://cdn.tiny.cloud/1/3cjqo5d7pff6xmehe029xmuygxw8mfi75mol7usnln12624i/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('guide-size.update',$size_guide->id) }}" method="post" id="formCreateSizeGuide" class="row g-3">
                            @csrf
                            @method('PUT')
                            <div class="col-12 mb-2">
                                <label class="form-label w-100" for="title">عنوان</label>
                                <div class="input-group input-group-merge">
                                    <input id="title" name="title" class="form-control" type="text" value="{{ $size_guide->title }}">
                                </div>
                                @error('title')
                                <x-dashboard::validation-error message="{{ $message }}"></x-dashboard::validation-error>
                                @enderror
                            </div>
                            <div class="col-12 mb-2">
                                <label class="form-label w-100" for="brand">انتخاب برند</label>
                                <div class="input-group input-group-merge">
                                    <select id="brand" name="brand" class="form-control">
                                        <option value="" selected disabled>برند را انتخاب کنید</option>
                                        @foreach(\Modules\Brand\Entities\Brand::all() as $brand)
                                            <option value="{{ $brand->id }}" @if($size_guide->brand->id === $brand->id) selected @endif>{{ $brand->title_fa }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                @error('brand')
                                    <x-dashboard::validation-error message="{{ $message }}"></x-dashboard::validation-error>
                                @enderror
                            </div>

                            <div class="col-12 mb-2">
                                <label class="form-label w-100" for="description">توضیحات</label>
                                <textarea id="description"  name="description">{!! $size_guide->description !!}</textarea>
                                @error('description')
                                    <x-dashboard::validation-error message="{{ $message }}"></x-dashboard::validation-error>
                                @enderror
                            </div>

                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary me-sm-3 me-1">
                                    بروزسانی راهنمای سایز
                                </button>
                                <a href="{{ route('guide-size.index') }}" type="reset" class="btn btn-label-secondary btn-reset">
                                    @lang('dashboard::common.cancel')
                                </a>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')

    <script>
        tinymce.init({
            language: 'fa',
            selector: 'textarea#description',
            plugins: 'autolink image link lists media table visualblocks',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        });
    </script>
@endsection
