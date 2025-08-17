@extends('dashboard::layouts.master')
@section('dashboardTitle','تسویه حساب مالی')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Cards with few info -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <span class="avatar-initial rounded-circle bg-label-primary"><i class="bx bx-dollar fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                    <h5 class="card-title mb-0 me-2 primary-font">{{ $report['total'] }}</h5>
                                    <small class="text-muted">مبلغ کل تراکنش‌ها</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <span class="avatar-initial rounded-circle bg-label-warning"><i class="bx bx-wallet fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                    <h5 class="card-title mb-0 me-2 primary-font">0</h5>
                                    <small class="text-muted"> موجودی قابل تسویه</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <span class="avatar-initial rounded-circle bg-label-success"><i class="bx bx-list-ol fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                    <h5 class="card-title mb-0 me-2 primary-font">{{ $report['sum'] }}</h5>
                                    <small class="text-muted">تعداد تراکنش</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <span class="avatar-initial rounded-circle bg-label-danger"><i class="bx bx-user fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                    <h5 class="card-title mb-0 me-2 primary-font">{{ count($merchants) }}</h5>
                                    <small class="text-muted">تعداد ذینفعان</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Cards with few info -->
        <div class="row">
            <div class="col-xl-12">
                @include('dashboard::partials.alert')
                <div class="card shadow-none bg-transparent">
                    <div class="card-body">
                        @if(count($merchants) == 0)
                            <div class="alert alert-warning">چیزی پیدا نشد!</div>
                        @else
                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover">
                                    <thead class="table-dark">
                                    <tr>
                                        <th>عنوان</th>
                                        <th>کاربر</th>
                                        <th>مرچنت ID</th>
                                        <th>موجودی</th>
                                        <th>درصد</th>
                                        <th>نوع ذینفع</th>
                                        <th>وضعیت</th>
                                        <th>عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                    @foreach($merchants as $merchant)
                                        <tr>
                                            <td>{{ $merchant->title }}</td>
                                            <td>
                                                <a href="#"
                                                   style="border-bottom: 1px dashed #fff"
                                                >{{ $merchant->user->full_name ?? $merchant->title }}</a>
                                            </td>
                                            <td dir="rtl">{{ $merchant->merchant_ID }}</td>
                                            <td>{{ number_format($merchant->balance) }} ریال </td>
                                            <td>{{ $merchant->percent }}</td>
                                            <td>{{ $merchant->type }}</td>
                                            <td>
                                                @if($merchant->status == "active")
                                                    <span class="badge bg-label-success">فعال</span>
                                                @else
                                                    <span class="badge bg-label-warning">غیرفعال</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-inline-block text-nowrap">
                                                    <a href="{{ route('dashboard.checkout.request',$merchant->id) }}"
                                                       class="btn btn-sm btn-icon"><i class="bx bx-rocket"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        <div class="d-flex justify-content-center mt-2">
                            {{ $merchants->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
