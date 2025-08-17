@extends('dashboard::layouts.master')
@section('dashboardTitle',__('dashboard::guarantees.guarantees list'))
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <x-dashboard::breadcrumb :breadcrumb-name="__('dashboard::guarantees.guarantees list')"></x-dashboard::breadcrumb>
        <div class="row">
            <!-- Categories -->
            @include('dashboard::partials.sections.categories')
            <!-- /Categories -->

            <!-- guarantees list -->
            <div class="col-xl-9 col-lg-8 col-md-8">
                @include('dashboard::partials.alert')
                <div class="card overflow-hidden">
                    <div class="card-header">
                        <h5>@lang('dashboard::guarantees.guarantees list')</h5>
                    </div>
                    <div class="card-body">
                        <div class="card-header header-elements">
                            <h4 class="d-flex align-items-center">
                                    <span class="badge bg-label-secondary p-2 rounded me-3">
                                      <i class="bx bx-cube bx-sm"></i>
                                    </span>
                                @lang('dashboard::guarantees.guarantees list')
                            </h4>

                            <div class="card-header-elements ms-auto">
                                {{--@can('taxes_create')--}}
                                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                                            data-bs-target="#addGuaranteeCanvas" aria-controls="addGuaranteeCanvas">
                                        @lang('dashboard::guarantees.add guarantee')
                                    </button>
                              {{--  @endcan--}}

                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        {{--@can('taxes_view')--}}
                            @if($guarantees->isEmpty())
                                <div class="alert alert-warning alert-dismissible d-flex align-items-center"
                                     role="alert">
                                    <i class="bx bx-xs bx-message-alt-error me-2"></i>
                                    @lang('dashboard::guarantees.Unfortunately, no tax has been defined in the system yet!')
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table warehouse-list">
                                        <thead class="table-dark">
                                        <tr>
                                            <th>@lang('dashboard::guarantees.guarantee title')</th>
                                            <th>@lang('dashboard::guarantees.guarantee description')</th>
                                            <th>@lang('dashboard::guarantees.guarantee link')</th>
                                            <th>@lang('dashboard::common.operation')</th>
                                        </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0">
                                        @foreach($guarantees as $guarantee)
                                            <tr>
                                                <td>{{ $guarantee->title }}</td>
                                                <td>{{ $guarantee->description }}</td>
                                                <td>{{ $guarantee->link }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        {{--@can('taxes_edit')--}}
                                                            <a href="{{ route('dashboard.guarantees.edit',$guarantee) }}"
                                                               class="btn btn-sm btn-outline-primary btn-group">@lang('dashboard::common.edit')</a>
                                                        {{--@endcan--}}
                                                        {{--@can('taxes_delete')--}}

                                                                <form action="{{ route('dashboard.guarantees.destroy',$guarantee) }}"
                                                                      id="deleteGuaranteeConfirm-{{ $guarantee->id }}"
                                                                      method="post" class="btn-group">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm
                                                                delete-tax
                                                                btn-outline-danger"
                                                                            data-id="{{ $guarantee->id }}"
                                                                    >@lang('dashboard::common.delete')
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
                            @endif
                        {{--@endcan--}}
                    </div>
                </div>
            </div>
            <!-- /guarantees list -->

            <div class="offcanvas offcanvas-end" tabindex="-1" id="addGuaranteeCanvas"
                 aria-labelledby="addGuaranteeCanvasLabel">
                <div class="offcanvas-header">
                    <h5 id="addGuaranteeCanvasLabel" class="offcanvas-title">@lang('dashboard::guarantees.add guarantee')</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                </div>
                <div class="offcanvas-body my-3 mx-0 flex-grow-0">
                    <form method="post" action="{{ route('dashboard.guarantees.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="title">@lang('dashboard::guarantees.guarantee title')</label>
                            <input type="text" name="title" class="form-control" id="title"
                                   placeholder="@lang('dashboard::guarantees.guarantee title')">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="description">@lang('dashboard::guarantees.guarantee description')</label>
                            <input type="text" name="description" class="form-control" id="description"
                                   placeholder="@lang('dashboard::guarantees.guarantee description')">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="link">@lang('dashboard::guarantees.guarantee link')</label>
                            <input type="text" name="link" class="form-control" id="link"
                                   placeholder="@lang('dashboard::guarantees.guarantee link')">
                        </div>

                        <button type="submit"
                                class="btn btn-primary mb-2 d-grid w-100">@lang('dashboard::guarantees.add guarantee')</button>
                        <button type="button" class="btn btn-label-secondary d-grid w-100"
                                data-bs-dismiss="offcanvas">
                            @lang('dashboard::common.cancel')
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')

    <script>
        $(".delete-tax").on('click', function (event) {
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
                    $("#deleteGuaranteeConfirm-" + id).submit();
                }
            });
        });
    </script>
@endsection
