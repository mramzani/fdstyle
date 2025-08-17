@extends('dashboard::layouts.master')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/select2/select2.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/jquery-ui/jquery-ui.css') }}"/>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="card my-1">
                <div class="card-body">
                    <livewire:product::search-input />
                </div>
            </div>
            <div class="card my-1">
                <form action="{{ route('adjustments.store') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <livewire:product::product-table />
                    </div>
                    <button type="submit" class="alert alert-info">ارسال</button>
                </form>


            </div>
        </div>

    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/panel/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/jquery-ui/jquery.ui.autocomplete.html.js') }}"></script>
    <script>
        //------------product search----------------
        $('#product_search').autocomplete({
            minLength: 2,
            source: function (request, response) {
                let url = "{{ route('get-product',':term') }}";
                url = url.replace(':term', request.term);
                $.ajax({
                    url: url,
                    type: 'get',
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        console.log(data);
                        response(data);
                        response($.map(data, function(item) {
                            return {
                                label: item.value,
                                value: item.key
                            }
                        }));

                        if (data.length === 1) {
                            //addNewProduct(data[0]);
                        } else {

                        }
                    }
                });

            },

            html: true,
            select: function (event, ui) {
                event.preventDefault();
                console.log(ui.item);
                //addNewProduct(ui.item);
            },
        })
            .autocomplete( "instance" )._renderItem = function( ul, item ) {

            return $( "<li class='list-group-item'>" )
                .append('<span style="margin-right: 150px;">' + item.label + '</span>')
                .appendTo( ul );
        };


        //==========================

        $(".select_products").select2({
            templateResult: formatState,
        });

        function formatState(state) {
            var count;
            var style;
            var disable;
            if (!state.id) {
                return state.text.toUpperCase();
            }
            var productImg = $(state.element).attr('data-pimg');
            var productCount = $(state.element).attr('data-count');
            var productCode = $(state.element).attr('data-code');
            if (productCount === '0') {
                count = 'اتمام موجودی';
                style = 'secondary';
                disable = 'pointer-events:none;opacity:0.6;';
            } else {
                count = 'موجودی: ' + productCount;
                style = 'success';
                disable = '';
            }
            if (!productImg) {
                return state.text.toUpperCase();
            } else {
                return $(
                    '<span style="' + disable + '">' +
                    '<img style="border: 1px solid #9f9f9f;border-radius: 5px;" src="' + productImg + '" width="40px" /> '
                    + state.text.toUpperCase() + '<span>' + ' - ' + productCode + '</span>' +
                    '</span>' +
                    '<span class="badge bg-label-' + style + '" style="float: left; color: #fff" <b>' + count + '</b><span>'
                );
            }

        }
    </script>
@endsection
