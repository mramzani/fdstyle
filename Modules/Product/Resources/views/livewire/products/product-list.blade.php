<div>
    <div class="card">
        <div class="m-2">
            @include('dashboard::partials.alert')
        </div>
        <div class="card-datatable table-responsive">
            <div class="row mx-2 my-2">
                <!-- start search input -->
                <div class="col-md-8 col-sm-12">
                    <input type="text" class="form-control" wire:model.debounce.500ms="keyword"
                           placeholder="جستجو براساس نام و کدمحصول">
                </div>
                <!-- end search input -->
                <div class="col-md-4 col-sm-12 mt-2 mt-sm-2 mt-md-0">
                    @can('products_create')
                        <div class="position-relative d-flex justify-content-around w-100">
                            <a href="{{ route('products.create') }}" class="btn btn-primary">
                                   <span>
                                        <i class="bx bx-plus me-0"></i>
                                        <span>@lang('product::products.add product')</span>
                                   </span>
                            </a>
                            <form action="{{ route('product.update-price') }}" method="post" id="updatePriceForm">
                                @csrf
                                <button type="submit" class="btn btn-danger" id="updatePrice">
                                    <i class="bx bx-sync me-0"></i>
                                    @lang('product::products.update with variant price')
                                </button>
                            </form>
                        </div>
                    @endcan

                </div>
            </div>
            @can('products_view')
                <table class="table border-top table-responsive">
                    @if($products->isNotEmpty())
                        <thead>
                        <tr>
                            <th>@lang('product::products.product name')</th>
                            <th>@lang('product::products.barcode')</th>
                            <th>@lang('dashboard::common.category')</th>
                            <th>@lang('dashboard::common.brand')</th>
                            @can('show_purchase_price')
                                <th>@lang('product::products.purchase price')</th>
                            @endcan
                            <th>@lang('product::products.sales price')</th>
                            {{--<th>@lang('product::products.current stock')</th>--}}
                            <th>@lang('dashboard::common.operation')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr id="{{ $product->id }}">
                                @php
                                    $currentStock = $product->variant()->count() > 0 ? $product->variant()->sum('quantity') : $product->detail->current_stock ;
                                @endphp
                                <td>
                                    <div class="d-flex justify-content-start align-items-center user-name">
                                        <div class="avatar-wrapper">
                                            <div class="avatar avatar-md me-3">
                                                <img class="rounded" src="{{ $product->image_url }}">
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="text-body text-truncate">
                                                <a href="{{ route('front.product.show',$product->id) }}" target="_blank">
                                                    <span class="fw-semibold">{{ $product->name }}</span>
                                                </a>
                                                <small>
                                                    موجودی:
                                                </small>
                                                <span class="badge
                                                    @if($currentStock > 2)
                                                    bg-label-success
                                                    @elseif($currentStock >= 1)
                                                    bg-label-warning
                                                    @elseif($currentStock == 0)
                                                    bg-label-danger
                                                    @endif ">{{ $currentStock }} عدد</span>
                                                <span class="badge bg-label-success">ردیف {{ $product->row ?? trans('dashboard::common.unknown') }} / طبقه : {{ $product->floor ?? trans('dashboard::common.unknown') }}</span>
                                                {!! $product->detail->promotion_price != null ? "<span class='badge badge-dot bg-danger'>" : '' !!}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td><span
                                        class="badge rounded-pill bg-label-secondary">{{ $product->barcode ?? trans('dashboard::common.unknown') }}</span>
                                </td>
                                <td>{{ $product->category->title_fa ?? trans('dashboard::common.unknown') }}</td>
                                <td>{{ $product->brand->title_fa ?? trans('dashboard::common.unknown') }}</td>
                                @can('show_purchase_price')
                                    <td>{{ number_format($product->detail->purchase_price ?? 0) ?? trans('dashboard::common.unknown') }}</td>
                                @endcan
                                <td>{{ number_format($product->detail->sales_price ?? 0) ?? trans('dashboard::common.unknown') }}</td>
                                {{--<td>
                                    {{ $currentStock }}
                                </td>--}}
                                <td>

                                    <div class="d-inline-block text-nowrap">
                                        @if($product->variant()->exists())
                                            <button onclick="variantList({{ $product->id }})"
                                                    class="btn btn-sm btn-icon"
                                                    type="button" data-bs-toggle="offcanvas"
                                                    data-bs-target="#offcanvasShowVariant"
                                                    aria-controls="offcanvasShowVariant">
                                                <i class="bx bx-list-ul"></i></button>
                                        @endif

                                            <a href="{{ route('product.attribute.edit',$product->id) }}"
                                               class="btn btn-sm btn-icon">
                                                <i class="bx bx-info-square"></i>
                                            </a>
                                        @can('products_edit')
                                            <a href="{{ route('products.edit',$product->id) }}"
                                               class="btn btn-sm btn-icon">
                                                <i class="bx bx-edit"></i></a>
                                        @endcan
                                        @can('view_product_variants')
                                            <a href="{{ route('products.variants.index',$product->id) }}"
                                               class="btn btn-sm btn-icon">
                                                <i class="bx bx-layer"></i></a>
                                        @endcan
                                            <button onclick="getStockHistory({{ $product->id }})"
                                                    class="btn btn-sm btn-icon"
                                                    type="button" data-bs-toggle="offcanvas"
                                                    data-bs-target="#offcanvasShowStockHistory"
                                                    aria-controls="offcanvasShowStockHistory"
                                            >
                                                <i class="bx bx-history"></i></button>
                                        @can('products_delete')
                                            <form action="{{ route('products.destroy',$product) }}"
                                                  id="deleteProductConfirm-{{ $product->id }}"
                                                  method="post" class="btn-group">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-icon
                                                    delete-product"
                                                        data-id="{{ $product->id }}"><i
                                                        class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    @else
                        <div class="alert alert-info m-4">
                            <span>@lang('product::products.No product has been created.')</span>
                            <a href="{{ route('products.create') }}"
                               class="btn btn-secondary btn-sm">@lang('product::products.add product')</a>
                        </div>
                    @endif

                </table>
            @endcan
        </div>
        <div class="d-flex justify-content-center my-1">
            {{ $products->links() }}
        </div>
    </div>
</div>
