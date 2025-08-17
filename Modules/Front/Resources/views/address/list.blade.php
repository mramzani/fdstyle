@extends('front::layouts.app')
@section('title','آدرس‌ها')

@section('mainContent')
    <!-- Start of Main -->
    <main class="page-content">
        <div class="container">
            <div class="row mb-5">
                @include('front::partials.profile-sidebar')
                <div class="col-lg-9 col-md-8">
                    <div class="section-title mb-2">
                        نشانی‌ها
                    </div>
                    <div class="checkout-section border rounded-md">
                        <div class="checkout-section-content">
                            <div class="row mx-0">
                                <div class="col-xl-3 col-lg-4 col-sm-6 mb-3">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="customRadio1" name="customRadio"
                                               class="custom-control-input">
                                        <label class="custom-control-label address-select" for="customRadio1">
                                            <span class="head-address-select">به این آدرس ارسال شود</span>
                                            <span>تهران، بلوار فرحزادی، نبش سیمای ایران</span>
                                            <span>
                                                    <i class="fa fa-user"></i>
                                                    جلال بهرامی راد
                                                </span>
                                            <a href="#" class="link--with-border-bottom edit-address-btn"
                                               data-toggle="modal" data-target="#editAddressModal">
                                                ویرایش
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-4 col-sm-6 mb-3">
                                    <div class="custom-control custom-radio">
                                        <button class="add-address" data-toggle="modal"
                                                data-target="#addAddressModal">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- editAddressModal -->
            <div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editAddressModalLabel">ویرایش آدرس</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="#">
                                <div class="row">
                                    <div class="col-lg-6 mb-3">
                                        <div class="text-sm text-muted mb-1">نام و نام خانوادگی:</div>
                                        <div class="text-dark font-weight-bold">
                                            <div class="form-element-row mb-0">
                                                <input type="text" class="input-element" value="جلال بهرامی راد">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <div class="text-sm text-muted mb-1">شماره موبایل:</div>
                                        <div class="text-dark font-weight-bold">
                                            <div class="form-element-row mb-0">
                                                <input type="text" class="input-element dir-ltr" value="090********">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <div class="text-sm text-muted mb-1">استان:</div>
                                        <div class="text-dark font-weight-bold">
                                            <div class="form-element-row mb-0">
                                                <select name="" id="" class="select2">
                                                    <option value="0">انتخاب استان</option>
                                                    <option value="1">خراسان شمالی</option>
                                                    <option value="2">تهران</option>
                                                    <option value="3">تبریز</option>
                                                    <option value="4">شیراز</option>
                                                    <option value="5">کرمان</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <div class="text-sm text-muted mb-1">شهر:</div>
                                        <div class="text-dark font-weight-bold">
                                            <div class="form-element-row mb-0">
                                                <select name="" id="" class="select2">
                                                    <option value="0">انتخاب شهر</option>
                                                    <option value="1">بجنورد</option>
                                                    <option value="2">شیروان</option>
                                                    <option value="3">اسفراین</option>
                                                    <option value="4">مانه و سملقان</option>
                                                    <option value="5">راز و جرگلان</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <div class="text-sm text-muted mb-1">آدرس کامل:</div>
                                        <div class="text-dark font-weight-bold">
                                            <div class="form-element-row mb-0">
                                            <textarea name="address" id="address" cols="30" rows="10"
                                                      class="input-element"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary">ذخیره تغییرات <i
                                    class="fad fa-money-check-edit"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end editAddressModal -->

            <!-- addAddressModal -->
            <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addAddressModalLabel">آدرس جدید</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="#">
                                <div class="row">
                                    <div class="col-lg-6 mb-3">
                                        <div class="text-sm text-muted mb-1">نام و نام خانوادگی:</div>
                                        <div class="text-dark font-weight-bold">
                                            <div class="form-element-row mb-0">
                                                <input type="text" class="input-element" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <div class="text-sm text-muted mb-1">شماره موبایل:</div>
                                        <div class="text-dark font-weight-bold">
                                            <div class="form-element-row mb-0">
                                                <input type="text" class="input-element dir-ltr" value="09*********">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <div class="text-sm text-muted mb-1">استان:</div>
                                        <div class="text-dark font-weight-bold">
                                            <div class="form-element-row mb-0">
                                                <select name="" id="" class="select2">
                                                    <option value="0">انتخاب استان</option>
                                                    <option value="1">خراسان شمالی</option>
                                                    <option value="2">تهران</option>
                                                    <option value="3">تبریز</option>
                                                    <option value="4">شیراز</option>
                                                    <option value="5">کرمان</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <div class="text-sm text-muted mb-1">شهر:</div>
                                        <div class="text-dark font-weight-bold">
                                            <div class="form-element-row mb-0">
                                                <select name="" id="" class="select2">
                                                    <option value="0">انتخاب شهر</option>
                                                    <option value="1">بجنورد</option>
                                                    <option value="2">شیروان</option>
                                                    <option value="3">اسفراین</option>
                                                    <option value="4">مانه و سملقان</option>
                                                    <option value="5">راز و جرگلان</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <div class="text-sm text-muted mb-1">آدرس کامل:</div>
                                        <div class="text-dark font-weight-bold">
                                            <div class="form-element-row mb-0">
                                            <textarea name="address" id="address" cols="30" rows="10"
                                                      class="input-element"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary">ذخیره تغییرات <i
                                    class="fad fa-money-check-edit"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end addAddressModal -->
        </div>
    </main>
    <!-- End of Main -->
@endsection
