





/*function checkPayingAmount(payingAmount, total) {
            let dueLabel = $('#due_label');
            if (payingAmount > total){
                $('#due_text').text('باقی‌مانده بستانکاری:');
                dueLabel.removeClass('bg-label-danger');
                dueLabel.addClass('bg-label-warning');
            } else if (payingAmount <= total){
                $('#due_text').text('باقی‌مانده بدهکاری:');
                dueLabel.removeClass('bg-label-warning');
                dueLabel.addClass('bg-label-danger');
            }
        }*/
//----------livewire event ---- ----

window.addEventListener('productAddedToTable', event => {
    playAudioBeep();
});
window.addEventListener('calculateSubTotal', event => {
    calculateSubTotal();
});

//-----------------common------------------

