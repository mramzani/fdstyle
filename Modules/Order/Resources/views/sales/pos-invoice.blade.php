<html lang="fa">
<head>
    <title>فاکتور {{ $order->invoice_number }} </title>
    <style>
        @import url("{{ asset('assets/panel/vendor/fonts/farsi-fonts-styles-fa-num/primary-iran-sans.css') }}");

        *, body {
            font-family: "primary-font", sans-serif !important;
        }

        .invoice-header {
            border-bottom: 1px dotted #ddd !important;
            text-align: center
        }

        .invoice-logo {
            width: 200px
        }

        .company-details {
            border-bottom: 2px dotted #ddd !important;
            margin-top: 5px;
            text-align: center
        }

        .company-address {
            margin-bottom: 0;
            white-space: pre
        }

        .invoice-customer-details {
            margin-bottom: 5px;
            width: 100%
        }

        .tax-invoice-title {
            margin-top: 5px;
            text-align: center
        }

        .tax-invoice-items {
            margin-top: 10px
        }

        .item-row {
            border-bottom: 1px dotted #ddd !important
        }

        .tax-invoice-totals {
            border-bottom: 2px dotted #ddd !important;
            border-top: 2px dotted #ddd !important;
            margin-top: 5px
        }

        .paid-amount-deatils {
            margin-top: 10px;
            text-align: center
        }

        .paid-amount-row {
            border-bottom: 2px dotted #ddd !important;
            border-top: 2px dotted #ddd !important
        }

        .thanks-details {
            margin-top: 5px;
            text-align: center
        }

        .barcode-details {
            margin-top: 10px;
            text-align: center
        }

        .footer-button {
            text-align: center !important
        }

        .invoice-header {
            border-bottom: 1px dotted #ddd !important;
            text-align: center
        }

        .invoice-logo {
            width: 100px
        }

        .company-details {
            border-bottom: 2px dotted #ddd !important;
            margin-top: 5px;
            text-align: center
        }

        .company-address {
            margin-bottom: 0;
            white-space: pre
        }

        .invoice-customer-details {
            margin-bottom: 5px;
            width: 100%
        }

        .tax-invoice-title {
            margin-top: 5px;
            text-align: center
        }

        .tax-invoice-items {
            margin-top: 10px
        }

        .item-row {
            border-bottom: 1px dotted #ddd !important
        }

        .tax-invoice-totals {
            border-bottom: 2px dotted #ddd !important;
            border-top: 2px dotted #ddd !important;
            margin-top: 5px
        }

        .paid-amount-deatils {
            margin-top: 10px;
            text-align: center
        }

        .paid-amount-row {
            border-bottom: 2px dotted #ddd !important;
            border-top: 2px dotted #ddd !important
        }

        .thanks-details {
            margin-top: 5px;
            text-align: center
        }

        .barcode-details {
            margin-top: 10px;
            text-align: center
        }

        .footer-button {
            text-align: center !important
        }
    </style>
</head>
<body>
<div id="pos-invoice" style="max-width:560px;margin:0 auto">
    <div style="max-width: 400px; margin: 0px auto;">
        <div class="invoice-header">
            <img class="invoice-logo" src="{{ company()->image_url }}" alt="">
        </div>
        <div class="company-details">
            <h2>{{ company()->site_title }}</h2>
            <p class="company-address">{{ company()->address }}</p>
            <h6 style="margin-bottom: 0px;">تلفن: {{ company()->phone }}</h6>
        </div>
        <div class="tax-invoice-details">
            <h3 class="tax-invoice-title">فاکتور فروش</h3>
            <table class="invoice-customer-details" dir="rtl">
                <tr>
                    <td style="width: 50%;">شماره فاکتور : {{ $order->invoice_number }}</td>
                    <td style="width: 50%;">تاریخ : {{ verta($order->order_date)->format('j %B Y - H:i') }}</td>
                </tr>
                <tr>
                    @if($order->order_type == 'sales')
                        <td>نام مشتری : {{ $order->customer->full_name }}</td>

                    @elseif($order->order_type == 'purchase')
                        <td>نام تامین‌کننده : {{ $order->supplier->full_name }}</td>
                    @endif
                </tr>
            </table>
        </div>
        <div class="tax-invoice-items">
            <table style="width: 100%;" dir="rtl">
                <thead class="bg-label-secondary">
                <td style="width: 5%;">#</td>
                <td style="width: 25%;">نام محصول</td>
                <td style="width: 20%;">تعداد</td>
                <td style="width: 25%;">قیمت</td>
                <td style="width: 25%; text-align: right;">جمع</td>
                </thead>
                <tbody>
                @foreach($order->products()->get() as $item)
                    <tr class="item-row">
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $item->name }} {{ $item->pivot->variant != null ? ' - ' . $item->pivot->variant->option->valuable->title : '' }}</td>
                        <td>{{ number_format($item->pivot->quantity) }} عدد</td>
                        <td>{{ number_format($item->pivot->unit_price) }} ت </td>
                        <td style="text-align: right;"> {{ number_format($item->pivot->subtotal) }} ت </td>
                    </tr>
                @endforeach
                <hr>
                <tr class="item-row-other">
                    <td colspan="3" style="text-align: right;">مبلغ فاکتور</td>
                    <td colspan="2" style="text-align: right;">{{ number_format($order->subtotal) }}<small> تومان </small></td>
                </tr>

                <tr class="item-row-other">
                    <td colspan="3" style="text-align: right;">تخفیف</td>
                    <td colspan="2" style="text-align: right;">{{ number_format($order->discount) }}<small> تومان </small></td>
                </tr>
                <tr class="item-row-other">
                    <td colspan="3" style="text-align: right;">حمل‌ونقل</td>
                    <td colspan="2" style="text-align: right;">{{ number_format($order->shipping) }}<small> تومان </small></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="tax-invoice-totals">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 30%;"><h6 style="margin-bottom: 0px;">تعداد
                            اقلام: {{ number_format($order->total_items) }}</h6></td>
                    <td style="width: 30%;"><h6 style="margin-bottom: 0px;">
                            تعداد: {{ number_format($order->total_quantity) }}</h6></td>
                    <td style="width: 40%; text-align: center;"><h6 style="margin-bottom: 0px;">جمع
                            کل: {{ number_format($order->total) }}</h6></td>
                </tr>
            </table>
        </div>
        <div class="paid-amount-deatils">
            <table style="width: 100%;">
                <thead class="bg-label-primary">
                <td style="width: 50%;">پرداخت شده</td>
                <td style="width: 50%;">مانده</td>
                </thead>
                <tbody>
                <tr class="paid-amount-row">
                    <td>{{ number_format($order->paid_amount) }}</td>
                    <td>{{ number_format($order->due_amount) }}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="barcode-details">
        </div>
        <div class="thanks-details"><h3>از خریدتان متشکریم</h3></div>
    </div>
</div>
</body>
</html>


