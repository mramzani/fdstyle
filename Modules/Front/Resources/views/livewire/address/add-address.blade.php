<div>
    <form wire:submit.prevent="addAddress">
        <div class="row">
            <div class="col-md-12">
                <label class="container-checkbox">
                    تحویل گیرنده خودم هستم
                    <input type="checkbox" wire:change="transfereeSelf" wire:model="transfereeCheck">
                    <span class="checkmark"></span>
                </label>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="text-sm text-muted mb-1">نام و نام خانوادگی:</div>
                <div class="text-dark font-weight-bold">
                    <div class="form-element-row mb-0">
                        <input type="text" class="input-element @error('full_name') border-danger @enderror"
                               name="full_name" wire:model="full_name"
                               placeholder="نام و نام خانوادگی تحویل گیرنده" {{ $readonly }}>
                    </div>
                    @error('full_name')
                    @include('front::partials.validation')
                    @enderror
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="text-sm text-muted mb-1">شماره موبایل:</div>
                <div class="text-dark font-weight-bold">
                    <div class="form-element-row mb-0">
                        <input type="text" class="input-element dir-ltr @error('mobile') border-danger @enderror"
                               maxlength="11" name="mobile" placeholder="شماره موبایل تحویل گیرنده"
                               wire:model="mobile" {{ $readonly }}>
                    </div>
                    @error('mobile')
                    @include('front::partials.validation')
                    @enderror
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="text-sm text-muted mb-1">استان:</div>
                <div class="text-dark font-weight-bold">
                    <div class="form-element-row mb-0">
                        <select name="province" id="province" class="form-control @error('province') border-danger @enderror"
                                wire:change="changeProvince" wire:model="province">
                            <option>لطفا استان را انتخاب کنید</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province->id }}">{{ $province->name_fa }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('province')
                        @include('front::partials.validation')
                    @enderror
                </div>
            </div>
            <div class="col-lg-6 mb-3" >
                <div class="text-sm text-muted mb-1">شهر:</div>
                <div class="text-dark font-weight-bold">
                    <div class="form-element-row mb-0">
                        <select name="city" id="city" class="form-control @error('city') border-danger @enderror"
                                wire:loading.attr="disabled"
                                wire:model.defer="city">
                            <option>انتخاب شهر</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name_fa }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('city')
                        @include('front::partials.validation')
                    @enderror
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="text-sm text-muted mb-1">آدرس کامل:</div>
                <div class="text-dark font-weight-bold">
                    <div class="form-element-row mb-0">
                                            <textarea name="address" wire:model.defer="address" id="address" cols="5" rows="5"
                                                      class="input-element"></textarea>
                    </div>
                    @error('address')
                        @include('front::partials.validation')
                    @enderror
                </div>
            </div>

            <div class="col-lg-6 mb-3">
                <div class="text-sm text-muted mb-1">پلاک:</div>
                <div class="text-dark font-weight-bold">
                    <div class="form-element-row mb-0">
                        <input type="text" class="input-element @error('plaque') border-danger @enderror"
                               wire:model.defer="plaque"
                               placeholder="در صورتی که پلاک ندارید 0 وارد کنید">
                    </div>
                    @error('plaque')
                        @include('front::partials.validation')
                    @enderror
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="text-sm text-muted mb-1"> کدپستی:</div>
                <div class="text-dark font-weight-bold">
                    <div class="form-element-row mb-0">
                        <input type="text" class="input-element dir-ltr @error('postal_code') border-danger @enderror"
                               maxlength="10" wire:model.defer="postal_code" placeholder="کد پستی 10 رقمی">
                    </div>
                    @error('postal_code')
                        @include('front::partials.validation')
                    @enderror
                </div>
            </div>

            <div class="col-12 mb-3">
                <button type="submit" class="btn btn-primary">افزودن آدرس <i
                        class="fad fa-money-check-edit"></i></button>
            </div>
        </div>
    </form>
</div>
