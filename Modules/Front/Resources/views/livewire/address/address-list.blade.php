<div>
    {{--<div class="summeryLoading" wire:loading wire:target="addressDefault"
         style="position: fixed;width: 100%;height: 100%;top: 0;left: 0;right: 0;bottom: 0;z-index: 2;"></div>--}}
    <div class="row mx-0">
        @foreach($addresses as $address)
            <div class="col-xl-3 col-lg-4 col-sm-6 mb-3" wire:click="addressDefault({{ $address->id }})">
                <div class="custom-control custom-radio">
                    <input type="radio" id="customRadio{{$address->id}}" name="customRadio"
                           @if($address->id == session()->get('default_address'))
                               checked
                           @endif
                           class="custom-control-input">
                    <label class="custom-control-label address-select text-muted" for="customRadio{{$address->id}}">
                        <span class="head-address-select">به این آدرس ارسال شود</span>
                        <span>{{ $address->address }}</span>
                        <span><i class="fa fa-user"></i>
                            {{ $address->transferee }} | {{ $address->mobile }}
                        </span>
                        <span><i class="fa fa-city"></i>
                            {{ $address->city->province->name_fa }} | {{ $address->city->name_fa }}
                        </span>
                    </label>
                </div>
            </div>
        @endforeach

        <div class="col-xl-3 col-lg-4 col-sm-6 mb-3">
            <div class="custom-control custom-radio">
                <button class="add-address" data-toggle="modal"
                        data-target="#addAddressModal">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-plus"></i>
                        <span class="mx-1">افزودن آدرس جدید</span>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>
