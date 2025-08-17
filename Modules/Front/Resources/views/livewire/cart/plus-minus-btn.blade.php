<div class="item-quantity">
    <div class="num-block">
        <div class="num-in">
            <span class="plus" wire:click="up">
                <i class="far fa-plus"></i>
            </span>
            <input type="text" class="in-num" wire:model.lazy="qty" readonly>

            @if($qty == 1)
                <span class="minus text-muted">
                    <i class="far fa-solid fa-minus"></i>
                </span>
            @else
                <span class="minus" wire:click="down">
                        <i class="far fa-solid fa-minus"></i>
                </span>
            @endif
        </div>
    </div>
    <button class="item-remove-btn mr-3" wire:click="remove">
        <i class="far fa-trash-alt"></i>
        حذف
    </button>
</div>

