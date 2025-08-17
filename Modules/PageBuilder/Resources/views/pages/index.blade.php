@extends('dashboard::layouts.master')
@section('dashboardTitle','مدیریت صفحات سایت')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <x-dashboard::breadcrumb breadcrumb-name="مدیریت صفحات"></x-dashboard::breadcrumb>

        <!-- Brands List Table -->
        {{--@can('brands_view')--}}
        <div class="card">
            <div class="m-2">
                @include('dashboard::partials.alert')
            </div>
            <div class="card-datatable table-responsive">

                {{-- @can('brands_create')--}}
                <div class="row mx-2">
                    <div class="alert alert-info">
                        <h6>راهنمای ایجاد صفحات:</h6>
                        <span>لطفا برای ایجاد صفحات زیر از اسلاگ (slug) ذکر شده استفاده شود</span>
                        <ul>
                            <li>نحوه ثبت سفارش : purchase-guide</li>
                            <li>رویه ارسال سفارش : shipping-methods</li>
                            <li>شیوه‌های پرداخت : payment-methods</li>
                            <li>سوالات مشتریان : faq</li>
                            <li>شرایط بازگشت کالا : return-policy</li>
                            <li>شرایط استفاده : terms-conditions</li>
                            <li>حریم خصوصی : privacy-policy</li>
                            <li>پیگیری سفارش : order-tracking</li>
                            <li>ارتباط با ما : contact-us</li>
                            <li>درباره ما : about-us</li>
                        </ul>
                    </div>
                    <div class="my-2 position-relative">
                        <a href="{{ route('dashboard.page.create') }}" class="btn btn-primary">
                           <span>
                                <i class="bx bx-plus me-0"></i>
                                <span class="d-none d-lg-inline-block">ایجاد صفحه جدید</span>
                           </span>
                        </a>
                    </div>
                </div>
                {{-- @endcan--}}

                <table class="table border-top table-responsive">
                    <thead>
                    <tr>
                        <th>عنوان صفحه</th>
                        <th>اسلاگ</th>
                        <th>وضعیت</th>
                        <th>@lang('dashboard::common.operation')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($pages as $page)
                        <tr>
                            <td>
                                <span class="text-body text-truncate"><span class="fw-semibold">{{ $page->title }}</span></span>
                            </td>
                            <td>{{ $page->slug ?? __('dashboard::common.unknown') }}</td>

                            <td>{!! $page->status !!}</td>
                            <td>
                                <div class="d-inline-block text-nowrap">
                                    {{-- @can('brands_edit')--}}
                                    <a href="{{ route('dashboard.page.edit',$page->id) }}" class="btn btn-sm btn-icon">
                                        <i class="bx bx-edit"></i></a>
                                    {{--@endcan--}}

                                    {{--@can('brands_delete')--}}
                                    <form action="{{ route('dashboard.page.destroy',$page->id) }}"
                                          id="deletePageConfirm-{{ $page->id }}"
                                          method="post" class="btn-group">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-icon
                                                    delete-page"
                                                data-id="{{ $page->id }}"><i
                                                class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                    {{--@endcan--}}

                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
            <div class="d-flex justify-content-center my-1">
                {{ $pages->links() }}
            </div>

        </div>
        {{-- @endcan--}}
    </div>
@endsection
@section('script')
    <script>
        $(".delete-page").on('click', function (event) {
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
                    $("#deletePageConfirm-" + id).submit();
                }
            });
        });
    </script>
@endsection
