@extends('dashboard::layouts.master')
@section('dashboardTitle','ویرایش صفحه')
@section('styles')
    <script src="https://cdn.tiny.cloud/1/3cjqo5d7pff6xmehe029xmuygxw8mfi75mol7usnln12624i/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <form action="{{ route('dashboard.page.update',$page) }}" method="post">
            @csrf
            @method('PUT')
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
                                <label class="form-label input-required" for="title">عنوان صفحه</label>
                                <input type="text" class="form-control @error('title') border-danger @enderror"
                                       value="{{ old('title',$page->title) }}" id="title" name="title" placeholder="عنوان صفحه">
                                @error('title')
                                <x-dashboard::validation-error message="{{ $message }}"></x-dashboard::validation-error>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label input-required" for="slug">اسلاگ</label>
                                <input type="text" class="form-control @error('slug') border-danger @enderror"
                                       value="{{ old('slug',$page->slug) }}" id="slug" name="slug" placeholder="اسلاگ صفحه">
                                @error('slug')
                                <x-dashboard::validation-error message="{{ $message }}"></x-dashboard::validation-error>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label w-100" for="description">توضیحات</label>
                                <textarea id="description" name="description">{{ old('description',$page->description) }}</textarea>
                                @error('description')
                                <x-dashboard::validation-error message="{{ $message }}"></x-dashboard::validation-error>
                                @enderror
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
                                    @foreach(\Modules\PageBuilder\Entities\Page::STATUS as $key => $value)
                                        <option value="{{ $key }}" @if($page->status == $key) selected @endif>{{ $value }}</option>
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

        tinymce.init({
            language: 'fa',
            selector: 'textarea#description',
            plugins: 'autolink image link lists media table visualblocks',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        });
    </script>
@endsection
