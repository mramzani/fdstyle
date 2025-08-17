@if(session()->has('alertPrimary'))
    <div class="alert alert-primary alert-dismissible d-flex align-items-center">
        <i class="bx bx-xs bx-info-circle me-2"></i>
        {{session()->get('alertPrimary') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session()->has('alertDanger'))
    <div class="alert alert-danger alert-dismissible d-flex align-items-center" role="alert">
        <i class="bx bx-xs bx-message-alt-error me-2"></i>
        {{session()->get('alertDanger') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session()->has('alertWarning'))
    <div class="alert alert-warning alert-dismissible d-flex align-items-center" role="alert">
        <i class="bx bx-xs bx-message-alt-error me-2"></i>
            {{session()->get('alertWarning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session()->has('alertSuccess'))
    <div class="alert alert-success alert-dismissible d-flex align-items-center" role="alert">
        <i class="bx bx-xs bx-check me-2"></i>
        {{ session()->get('alertSuccess') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session()->has('alertInfo'))
    <div class="alert alert-info alert-dismissible d-flex align-items-center" role="alert">
        <i class="bx bx-xs bx-info-square me-2"></i>
        {{ session()->get('alertInfo') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
