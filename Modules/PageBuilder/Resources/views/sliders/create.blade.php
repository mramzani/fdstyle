@extends('dashboard::layouts.master')
@section('dashboardTitle','ایجاد اسلایدر جدید')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <form action="{{ route('dashboard.sliders.store') }}" method="post">
            @csrf
            <div class="row">
                @include('dashboard::partials.alert')
                <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label input-required" for="name">نام اسلایدر</label>
                                <input type="text" class="form-control @error('name') border-danger @enderror" value="{{ old('name','') }}" id="name" name="name" placeholder="نام اسلایدر">
                                @error('name')
                                @include('dashboard::partials.validation-error')
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label input-required" for="key">کلید</label>
                                <input type="text" class="form-control @error('key') border-danger @enderror" value="{{ old('key','') }}" id="key" name="key" placeholder="کلید">
                                @error('key')
                                    @include('dashboard::partials.validation-error')
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="description">توضیحات</label>
                                <textarea id="description" class="form-control" name="description"
                                          placeholder="توضیحات را اینجا بنویسید"></textarea>
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
