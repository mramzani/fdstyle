<div>
    <div x-data="xData()" @click.outside="close()">
        <div class="position-relative">
            <input type="text" class="form-control" placeholder="نام محصول یا بارکد | حداقل سه حرف"
                   wire:model.debounce.1000ms="keyword"
                   @keydown.arrow-down="next()"
                   @keydown.arrow-up="previous()"
                   @keydown.enter.prevent="select()">
            @if($open and strlen($keyword) >= 3)
                <ul class="list-group position-absolute bg-label-secondary mt-1 border border-gray rounded w-100 zindex-5">
                    @if($products->count() > 0 )
                        @foreach($products as $key => $product)
                            <li wire:key="item-{{ $loop->index + 1000 }}" wire:click="select({{ $loop->index }})"
                                x-data="{ index: {{ $loop->index }} }"
                                class="list-group-item px-3 py-2 text-white cursor-pointer d-flex align-items-center text-white"
                                :class="{'bg-primary text-white': index === highlighted }">
                                <img src="{{ $product['image'] }}" alt="" class="rounded me-3 w-px-50">
                                <div class="w-100">
                                    <div class="d-flex justify-content-between">
                                        <div class="product-info">
                                            <h6 class="mb-1">{{ $product['name'] }}</h6>
                                            @if($product['current_stock'] == 0)
                                                <div class="product-status">
                                                    <span class="badge badge-dot bg-danger"></span>
                                                    <small>ناموجود</small>
                                                </div>
                                            @else
                                                <div class="product-status">
                                                    <span class="badge badge-dot bg-success"></span>
                                                    <small>موجودی: {{ $product['current_stock'] }}</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    @else
                        <div class="list-group-item d-flex align-items-center cursor-pointer">
                            <div class="w-100">
                                <div class="d-flex justify-content-between">
                                    <div class="product-info">
                                        <h6 class="mb-1">محصول با این مشخصات پیدا نشد و یا موجود نیست!</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </ul>
            @endif
        </div>
    </div>
    @once
        <script>
            function xData() {
                return {
                    index: null,
                    highlighted: 0,
                    count: null,
                    results: @entangle('products'),
                    init(){
                        this.$watch('results', () => this.clearResultsCount())
                    },
                    clearResultsCount() {
                        this.count = null
                        this.highlighted = 0;
                    },
                    next() {
                        if (this.hasNoResults()) return this.resetFocus()
                        this.highlighted = (this.highlighted + 1) % this.count;
                    },
                    previous() {
                        if (this.hasNoResults()) return this.resetFocus()
                        this.highlighted = (this.highlighted + this.count - 1) % this.count;
                    },
                    select() {
                        this.$wire.call('select', this.highlighted)
                    },
                    close() {
                        if (this.$wire.open) {
                            this.$wire.open = false;
                        }
                    },
                    //===============
                    hasResults() {
                        return this.totalResults() > 0
                    },

                    hasNoResults() {
                        return !this.hasResults()
                    },
                    totalResults() {
                        if (this.count) return this.count
                        this.count = this.results ? this.results.length : 0
                    },
                    resetFocus() {
                        return this.index = null;
                    }
                }
            }
        </script>
    @endonce
</div>
