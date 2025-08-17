document.onkeydown = function (event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        return false;
    }
    if (event.altKey === true && event.code === 'KeyP') {
        $("#PaymentBtn").trigger('click');
    }
};

function playAudioBeep() {
    const audio = $("#beep")[0];
    audio.play();
}

function calculateSubTotal() {
    //calculate total_items

    const tableTrEl = $('#orderList >tbody >tr');
    var total_items = tableTrEl.length;

    //calculate total_quantity

    var total_quantity = 0;
    var subtotal = 0;

    tableTrEl.each(function (index) {
        let qty = parseInt($("#quantity_" + index).val()) || 1;
        let unit_price = parseInt($("#unit_price_" + index).val());
        let unit_discount = parseInt($("#unit_discount_" + index).val()) || 0;
        let total_discount = qty * unit_discount;
        let rowPrice = (qty * unit_price) - total_discount;

        $("#subtotal_" + index).val(rowPrice);
        $("#row_price_" + index).text(rowPrice.toLocaleString(undefined, {minimumFractionDigits: 0}));
        $("#txt_total_discount_" + index).text(total_discount.toLocaleString(undefined, {minimumFractionDigits: 0}));
        $("#total_discount_" + index).val(total_discount);

        total_quantity += parseInt($(this).find('.quantity').val()) || 1;
        subtotal += parseInt($(this).find('.subtotal').val());
    })


    // sum all subtotal
    $('#total_items').val(total_items);
    $('#total_quantity').val(total_quantity);
    $('#subtotal').val(subtotal);
    $('#subtotal_label').text(subtotal.toLocaleString(undefined, {minimumFractionDigits: 0}));

    // sum all discount + discount amount
    calculateTotal();

}

function calculateTotal() {
    let subtotal = parseInt($('#subtotal').val());
    let shipping = parseInt($('#shipping').val());
    let discount = parseInt($('#discount').val());

    let total = subtotal;

    if (shipping !== 0) {
        total += shipping;
    }
    if (discount !== 0) {
        total -= discount;
    }

    $('#total_label').text(total.toLocaleString(undefined, {minimumFractionDigits: 0}));

    $('#total').val(total);
    $('#due_amount').val(total);
    $('#payable_amount').text(total.toLocaleString(undefined, {minimumFractionDigits: 0}))
    $('#canvas_due_amount').text(total.toLocaleString(undefined, {minimumFractionDigits: 0}))

}

function applyDiscount() {

    var discount = parseInt($('input[name="discount"]').val());

    let total = $('#total').val();

    if (isNaN(discount)) {
        alert('مقدار تخفیف نباید خالی باشد!');
        return;
    }
    if (discount >= total) {
        alert('مقدار تخفیف نباید بیشتر یا مساوی مجموع باشد!');
        return;
    }

    $('#discount').val(discount);

    calculateTotal();
}

function applyShipping() {
    var shipping = parseInt($('#shipping').val());
    if (isNaN(shipping)) {
        alert('مقدار حمل‌و‌نقل نباید خالی باشد!');
        return;
    }

    calculateTotal();
}

function changeAmount(input) {
    let payingAmount = $(input).val() !== '' ? parseInt($(input).val()) : 0;
    let total = parseInt($('#total').val());
    let dueAmount;


    if (payingAmount > total) {
        //TODO:: change to sweet alert
        alert('مبلغ پرداختی نمی‌تواند بیشتر از مبلغ فاکتور باشد.');
        $(input).val(total);
        payingAmount = total;
        dueAmount = 0;
    } else {
        dueAmount = total - payingAmount;
    }

    //check paying amount and change label color , for when allowed paying amount > total amount
    //چک کردن مبلغ درحال پرداخت،تغییر رنگ بکگراند،برای زمانی که اجازه بدیم مبلغ پرداخت بیشتر از مبلغ فاکتور باشد
    //checkPayingAmount(payingAmount,total);
    replaceAmount(payingAmount, dueAmount);

}

function replaceAmount(payingAmount, dueAmount) {
    $('#paid_amount').val(payingAmount);
    $('#due_amount').val(dueAmount);
    $('#paying_amount').text(parseInt(payingAmount).toLocaleString(undefined, {minimumFractionDigits: 0}));
    $('#canvas_due_amount').text(Math.abs(dueAmount).toLocaleString(undefined, {minimumFractionDigits: 0}));
}

function playAudioBeepTimber() {
    const audio = $("#beep-timber")[0];
    audio.play();
}

function showTable() {
    $("table#orderList").removeClass('d-none');
}

function hideTable() {
    $("table#orderList").addClass('d-none');
}

function checkTableDisplay() {
    let countTrEl = $('table#orderList >tbody>tr').length;
    if (countTrEl > 0) {
        showTable();
    } else if (countTrEl <= 0) {
        hideTable();
    }
}

function clearSearchInput() {
    $("#search").val('');
}

function enablePaymentBtn() {
    $('#PaymentBtn').attr('disabled', false);
}

function resetAll() {

    setTimeout(function () {
        location.reload()
    }, 5000);
}


