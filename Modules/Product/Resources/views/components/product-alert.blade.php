@if($count != 0)
    <div class="alert alert-warning">
        @lang('product::products.there is product without',
                ['count' => $count
                , 'variable' => $variable])
    </div>
@endif
