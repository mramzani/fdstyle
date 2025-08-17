@extends('dashboard::layouts.master')
@section('dashboardTitle','افزودن تنظیم موجودی')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="card my-1">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @can('stock_adjustments_create')
                <div class="card-body">
                    <livewire:product::products.product-search-input />
                </div>
                @endcan
            </div>
            @can('stock_adjustments_create')
            <div class="card my-1">
                    <form action="{{ route('adjustments.store') }}" method="post">
                        @csrf
                        <div class="card-body">
                            <livewire:product::adjustments.product-search-result />
                        </div>
                        <button type="submit" class="alert alert-info">ارسال</button>
                    </form>
            </div>
            @endcan
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        /*var li = $('li');
        var liSelected;
        $(window).keydown(function(e) {
            if(e.which === 40) {
                if(liSelected) {
                    liSelected.removeClass('selected');
                    next = liSelected.next();
                    if(next.length > 0) {
                        liSelected = next.addClass('selected');
                    } else {
                        liSelected = li.eq(0).addClass('selected');
                    }
                } else {
                    liSelected = li.eq(0).addClass('selected');
                }
            } else if(e.which === 38) {
                if(liSelected) {
                    liSelected.removeClass('selected');
                    next = liSelected.prev();
                    if(next.length > 0) {
                        liSelected = next.addClass('selected');
                    } else {
                        liSelected = li.last().addClass('selected');
                    }
                } else {
                    liSelected = li.last().addClass('selected');
                }
            }
        });*/
    </script>
@endsection
