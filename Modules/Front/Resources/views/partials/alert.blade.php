@if(session()->has('alertSuccess'))
    <div class="alert alert-success d-flex align-items-center">
        <i class="fa fa-info-circle ml-2"></i>
        {{session()->get('alertSuccess') }}
    </div>
@endif
@if(session()->has('alertWarning'))
    <div class="alert alert-warning d-flex align-items-center">
        <i class="fa fa-info-circle ml-2"></i>
        {{session()->get('alertWarning') }}
    </div>
@endif
