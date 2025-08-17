$(document).ready(function (){
    toastr.options = {
        "progressBar": true,
        "positionClass": "toast-bottom-right",
        "showDuration": "500",
        "hideDuration": "4000",
        "timeOut": "5000",
        "extendedTimeOut": "3000",
        "preventDuplicates": true,
    }
});

window.addEventListener('show-offCanvas',event => {
    toggleOffCanvas(event.detail.offCanvas);
});

window.addEventListener('hide-offCanvas',event => {
    toastr.success(event.detail.message,"عملیات با موفقیت انجام شد!");
    //toggleOffCanvas();
    location.reload();
});

function toggleOffCanvas(offCanvas){
    var addOffCanvas = $("#add" + offCanvas + "OffCanvas");

    var OffCanvas = new bootstrap.Offcanvas(addOffCanvas);
    OffCanvas.toggle();
}

window.addEventListener('confirm-delete',event => {
    Swal.fire({
        title: 'آیا از حذف این مورد اطمینان دارید؟',
        text: 'در صورت حذف اطلاعات از بین خواهد رفت!',
        showCancelButton: true,
        confirmButtonText: 'تایید',
        cancelButtonText: 'انصراف',
        buttonsStyling: false,
    }).then(function (result) {
        if (result.isConfirmed) {
            Livewire.emit('deleteConfirmed');
        }
    });
});

window.addEventListener('Deleted',event => {
    toastr.success(event.detail.message,"عملیات با موفقیت انجام شد!");
});
