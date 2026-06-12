"use strict"
// currency format
function currencyFormat(amount, type = "icon", decimals = 2) {
    let symbol = $("#currency_symbol").val();
    let position = $("#currency_position").val();
    let code = $("#currency_code").val();

    let formatted_amount = formattedAmount(amount, decimals);

    // Apply currency format based on the position and type
    if (type === "icon" || type === "symbol") {
        if (position === "right") {
            return formatted_amount + symbol;
        } else {
            return symbol + formatted_amount;
        }
    } else {
        if (position === "right") {
            return formatted_amount + " " + code;
        } else {
            return code + " " + formatted_amount;
        }
    }
}
// Format the amount
function formattedAmount(amount, decimals) {
    return Number.isInteger(+amount)
        ? parseInt(amount)
        : (+amount).toFixed(decimals);
}


$(document).on("click", ".getCoupon", function () {
    let url = $("#getSaleCoupon").val();

    $("#couponList").html(
        '<div class="text-center p-3">Loading coupons...</div>'
    );

    $.get(url, function (coupons) {
        if (coupons.length === 0) {
            $("#couponList").html(
                '<div class="text-center text-muted">No coupons available</div>'
            );
            return;
        }
        let colors = [
            "custom-bg-green",
            "custom-bg-orange",
            "custom-bg-blue",
            "custom-bg-pink",
        ];
        let html = "";
        coupons.forEach((coupon) => {
            // Pick random color
            let randomColor = colors[Math.floor(Math.random() * colors.length)];

            let description = coupon.description ?? "";
            if (description.length > 20) {
                description = description.substring(0, 50) + "...";
            }

            html += `
            <div class="coupon-card ${randomColor} mb-2">
              <span class="circle-top"></span>
                <span class="circle-bottom"></span>
                <div>
                    <div class="coupon-discount text-success">
                        ${
                            coupon.discount_type === "percentage"
                                ? coupon.discount + "%"
                                : currencyFormat(coupon.discount)
                        }
                        <br><small class="discount-text">Discount</small>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-end w-100">
                <div class="coupon-details">
                    <div class="fw-bold">${coupon.name ?? "Coupon Offer"}</div>
                    <div class="small text-muted">${description}</div>
                    <div class="small text-muted">Till: ${
                        coupon.end_date ?? "N/A"
                    }</div>
                    <div class="coupon-code">CODE: ${coupon.code}</div>
                </div>
                <button class="btn-apply applyCoupon" data-id="${
                    coupon.id
                }" data-discount="${coupon.discount}" data-type="${
                coupon.discount_type
            }">Apply</button>
                </div>
            </div>`;
        });

        $("#couponList").html(html);
    });
});

$(document).on("click", ".applyCoupon", function () {

   let subtotal = $(".subTotal").data('value');

    if (subtotal <= 0) {
        toastr.error("Subtotal is zero. Cannot apply coupon!");
        return;
    }

    let id = $(this).data("id");
    let discount = parseFloat($(this).data("discount"));
    let type = $(this).data("type");

    // Store coupon values
    $(".couponId").val(id);
    $(".coupondiscount").val(discount);
    $(".couponType").val(type);

    // Show the coupon div
    $("#appliedCouponBox").removeClass("d-none");

    // Update visible coupon amount
    if (type === "percentage") {
        $(".appliedCouponAmountShow").text(discount + "%");

    } else {
        $(".appliedCouponAmountShow").text(currencyFormat(discount));

    }

    $("#customerCouponModal").modal("hide");

    // Recalculate totals
    calculateTotal();
});

$(document).on("click", "#crossBtn", function () {

    // Clear coupon fields
    $(".couponId").val("");
    $(".coupondiscount").val("");
    $(".couponType").val("");
    $(".couponAmountVal").val(0);
    $(".couponPercentageVal").val(0);

    // Hide the coupon applied box
    $("#appliedCouponBox").addClass("d-none");

    // Recalculate totals without coupon
    calculateTotal();
});


function calculateTotal() {
    let type = localStorage.getItem("selected_order_type") || 'delivery';

    let subTotal = Number($(".subTotal").data("value"));
    let discountValue = Number($(".checkOutdiscount").data("value"));
    let discountType = $(".checkOutdiscount").data("type");
    let vatRate = Number($("#vatAmount").data("rate"));
    let couponType = $(".couponType").val();
    let couponValue = Number($(".coupondiscount").val());

    let deliveryCharge = 0;
    if (type === "delivery") {
        deliveryCharge = Number($("#deliveryCharge").data("value")) || 0;
    }
    //VAT calculation
    let vatAmount = subTotal * (vatRate / 100);
    $(".checkoutVatAmountValue").text(currencyFormat(vatAmount.toFixed(2)));
    $(".checkoutVatAmountValue").val(formattedAmount(vatAmount, 2));

    //discount calculation
    let discountAmount = 0;
    let discountPercentage = 0;

    if (discountType === "percentage") {
        discountPercentage = discountValue;
        discountAmount = (subTotal * discountValue) / 100;
        $(".discountAmountShow").text(discountPercentage + "%");
    } else if (discountType === "flat") {
        discountAmount = discountValue;
        discountPercentage = (discountValue / subTotal) * 100;
        $(".discountAmountShow").text(currencyFormat(discountAmount));
    }

    $(".discountAmountVal").val(formattedAmount(discountAmount, 2));
    $(".discountPercentageVal").val(formattedAmount(discountPercentage, 2));

    let discount = discountAmount;

    //coupon calculation
    let couponAmount = 0;
    let couponPercentage = 0;

    if (couponType === "percentage") {
        couponPercentage = couponValue;
        couponAmount = (subTotal * couponValue) / 100;

    } else if (couponType === "flat") {
        couponAmount = couponValue;
        couponPercentage = (couponValue / subTotal) * 100;
    }
    $(".couponAmountVal").val(formattedAmount(couponAmount, 2));
    $(".couponPercentageVal").val(formattedAmount(couponPercentage, 2));

    let couponDiscount = couponAmount;

    //Total payable
    let totalPayable = subTotal - couponDiscount - discount + vatAmount + deliveryCharge;

    $(".totalPayable").text(currencyFormat(totalPayable.toFixed(2)));
    $(".totalPayable").val(formattedAmount(totalPayable, 2));

}

calculateTotal();

$(window).on("load", function () {
    const type = localStorage.getItem("selected_order_type") || 'delivery';

    if (type) {
        $("#orderTypeInput").val(type);
    }

    if (type === 'delivery') {
        $(".deliveryChargeHideShow").removeClass("d-none");
    } else if (type === 'pick_up') {
        $(".deliveryChargeHideShow").addClass("d-none");
    } else if (type === 'dine_in') {
        $(".deliveryChargeHideShow").addClass("d-none");
    }
});

$(window).on("load", function () {
    const notes_value = localStorage.getItem("notes_value");
    $("#notes_value").val(notes_value);
});



$('.address-card').on('click', function() {
    let billing_id = $(this).data('id');

     $("#billingIdinput").val(billing_id);

     $(".address-card").removeClass("active");
     $(this).addClass("active");
})

let defaultId = $(".address-card.active").data("id");
$("#billingIdinput").val(defaultId);
