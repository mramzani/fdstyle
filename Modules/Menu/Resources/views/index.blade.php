@extends('dashboard::layouts.master')
@section('dashboardTitle','چیدمان منو اصلی سایت')

@section('styles')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('assets/panel/vendor/libs/doMenu/jquery.domenu-0.100.77.css') }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('assets/panel/vendor/libs/select2/select2.css') }}"/>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <x-dashboard::breadcrumb
            :breadcrumb-name="__('menu::menus.menus list')"></x-dashboard::breadcrumb>
        <!-- Category List Table -->
        <div class="card">
            <div class="m-2">
                @include('dashboard::partials.alert')
            </div>
            <div class="card-datatable table-responsive">
                <div class="row m-4">
                    <div class="col-6">
                        <button class="btn btn-warning" id="expandAll">بازکردن منوها</button>
                        <button class="btn btn-warning" id="collapseAll">بستن منوها</button>
                    </div>
                </div>
                <div class="row mx-2">
                    <div class="col-12">
                        <div class="alert alert-warning">توجه:‌ پس از تغییر متن منو حتما گزینه "ذخیره موقت" را بزنید</div>
                    </div>

                    <div class="col" style="direction: ltr;text-align: left">
                        <div class="dd" id="domenu-0">
                            <ol class="dd-list"></ol>
                            <button type="button" class="dd-new-item">+</button>
                            <li class="dd-item-blueprint">
                                <button class="expand" data-action="expand" type="button"
                                        style="display: none;margin-top:30px">+
                                </button>
                                <button class="collapse" data-action="collapse" type="button"
                                        style="display: none;margin-top:30px">–
                                </button>
                                <div class="dd-handle dd3-handle"></div>
                                <div class="dd3-content">
                                    <span class="item-name">[item_name]</span>
                                    <div class="dd-button-container">

                                        <button type="button" class="item-add"><i class="bx bxs-plus-circle"></i>
                                        </button>
                                        <button type="button" class="item-remove"
                                                data-confirm-class--="item-remove-confirm">
                                            <i class="bx bxs-trash"></i>
                                        </button>
                                    </div>
                                    <div class="dd-edit-box" style="display: none;">
                                        <div class="d-flex justify-content-between">
                                            <div class="col-4">
                                                <input type="text" class="form-control-sm w-75" name="title" autocomplete="off"
                                                       style="border: 1px solid #000000"
                                                       placeholder="عنوان منو"
                                                       data-placeholder="عنوان منو "
                                                       data-default-value="رسته جدید شماره . {?numeric.increment}">
                                            </div>
                                            <div class="col-6">
                                                <input type="text" name="menu_url" class="form-control-sm w-75 text-black"
                                                       style="border: 1px solid #000000"
                                                       placeholder="لینک را وارد کنید">

                                            </div>
                                            <div class="col-2">
                                                <button class="end-edit btn btn-danger btn-sm">ذخیره موقت</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </li>
                        </div>
                    </div>


                </div>

                <div class="row m-2">
                    <div class="col-5">
                        <div id="domenu-0-output">
                            <form action="{{ route('dashboard.menus.store') }}" method="POST">
                                @csrf
                                <textarea class="jsonOutput form-control d-none" name="json"></textarea>
                                <button type="submit" class="btn btn-primary">ذخیره نهایی</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <!-- End of Category List Table -->
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/panel/vendor/libs/doMenu/jquery.domenu-0.100.77.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/select2/select2.js') }}"></script>
    <script>
        $(document).ready(function () {
            var $domenu = $('#domenu-0'),
                domenu = $('#domenu-0').domenu(),
                $outputContainer = $('#domenu-0-output'),
                $jsonOutput = $outputContainer.find('.jsonOutput');

            $domenu.domenu({
                advanceEditFunction: 'advanceEdit',
                data: '@json($menus)',
                maxDepth: 2,
                select2:{
                    support: true,
                    params:  {
                        tags: true
                    }
                },
            }).parseJson()
                .on(['onItemCollapsed', 'onItemExpanded', 'onItemAdded', 'onSaveEditBoxInput', 'onItemDrop', 'onItemDrag', 'onItemRemoved', 'onItemEndEdit'], function (a, b, c) {
                    $jsonOutput.val(domenu.toJson());
                })
                .onItemMaxDepth(function () {
                    alert('عمق منو غیرمجاز می‌باشد');
                })

            $jsonOutput.val(domenu.toJson());
            $("body").on('click','#collapseAll',function () {
                domenu.collapseAll();
            });
            $("body").on('click','#expandAll',function () {
                domenu.expandAll();
            });
        });
    </script>
    <script>
        function advanceEdit($id) {
            if ($id === 0) {
                console.log(' این آیتم هنوز ثبت نهایی نشده ');
            }
        }
    </script>
@endsection
