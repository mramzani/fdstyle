<div class="modal fade" {{ $attributes }}  tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="secondary-font">{{ $header ?? '' }}</h3>
                </div>
                {{ $content ?? '' }}

            </div>
        </div>
    </div>
</div>
