<div>
    <form action="{{ \URL::signedRoute('front.order.store') }}" method="post">
        @csrf
        <div class="d-flex px-3 py-4">
            @if(!session()->exists('default_address'))
                <a class="btn btn-outline-primary btn-block py-2">آدرس را انتخاب کنید</a>
            @else
                <button type="submit" class="btn btn-primary btn-block py-2">پرداخت</button>
            @endif
        </div>
    </form>
</div>
