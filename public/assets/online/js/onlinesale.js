"use strict";

//currency js start

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

function formatNumber(num) {
    if (num >= 1000000) {
        return (num / 1000000).toFixed(1).replace(/\.0$/, "") + "M";
    } else if (num >= 1000) {
        return (num / 1000).toFixed(1).replace(/\.0$/, "") + "k";
    }
    return num;
}

// get number only
function getNumericValue(value) {
    return parseFloat(value.replace(/[^0-9.-]+/g, "")) || 0;
}

//item added in cart js start
let selectedProduct = {};

$(document).on("click", ".variation-product", function () {
    showProductModal($(this));
});

function showProductModal(element) {
    selectedProduct = {};

    selectedProduct = {
        productId: element.data("product-id"),
        productName: element.data("product-name"),
        category: element.data("category"),
        foodType: element.data("food-type"),
        preparationTime: element.data("preparation-time"),
        image: element.data("image"),
        description: element.data("description"),
        variations: element.data("variations") || [],
        modifierGroupOptions: element.data("modifier-groups-option") || [],
    };

    // Set modal display values
    $("#variationProductName").text(selectedProduct.productName);
    $("#category-food").text(
        selectedProduct.category + " - " + selectedProduct.foodType,
    );
    $("#preparation_time").text(selectedProduct.preparationTime + " min");
    $("#modalProductImage").attr("src", selectedProduct.image);
    $("#description").text(selectedProduct.description);

    //Render Variations
    let variationHtml = "";
    if (selectedProduct.variations.length > 0) {
        selectedProduct.variations.forEach((variation, index) => {
            variationHtml += `
                    <div class="option-card addons-item variation-item ${index === 0 ? "active" : ""}"
                        data-variation-id="${variation.id}">
                        <label class="option-label">
                            <input type="checkbox"
                                class="variation-option"
                                value="${variation.id}"
                                ${index === 0 ? "checked" : ""}>
                            <span class="custom-check"></span>
                            <span class="addons-name">${variation.name || "Variation"}</span>
                            <span class="price" data-price="${variation.price ?? 0}">${currencyFormat(variation.price ?? 0)}</span>
                        </label>
                    </div>
                `;
        });

        // Set default first variation as selected
        let firstVariation = selectedProduct.variations[0];
        selectedProduct.selectedVariation = {
            variation_id: firstVariation.id,
            price: firstVariation.price ?? 0,
        };
    } else {
        //If no variations
        variationHtml = `<p class="text-muted">No variations available</p>`;

        // Get base sales price from data attribute
        const basePrice = parseFloat(element.data("sales-price") ?? 0);

        selectedProduct.selectedVariation = {
            variation_id: null,
            price: basePrice,
        };
    }
    $("#variation-container").html(variationHtml);

    // Render Modifier Group
    let modifierHtml = "";
    if (selectedProduct.modifierGroupOptions.length > 0) {
        selectedProduct.modifierGroupOptions.forEach((group, index) => {
            let optionsHtml = "";
            group.options.forEach((option) => {
                if (option.is_available) {
                    optionsHtml += `
                    <label class="addons-item">
                        <input type="checkbox"
                            class="modifier-option"
                            data-modifier-id="${group.modifier_id}"
                            data-option-id="${option.id}"
                            data-name="${option.name}"
                            data-price="${option.price}"
                            data-group-id="${group.id}"
                            data-multiple="${group.is_multiple ? 1 : 0}">
                        <span class="custom-check"></span>
                        <span class="addons-name">${option.name}</span>
                        <span class="addons-price">${currencyFormat(
                            option.price ?? 0,
                        )}</span>
                    </label>
                    `;
                }
            });

            modifierHtml += `
                <div class="addons-section mt-3" data-required="${
                    group.is_required ? 1 : 0
                }">
                    <div class="addons-header d-flex align-items-center justify-content-between" style="cursor:pointer">
                     <div class="d-flex align-items-center svg-toggle ">
                        <span class="circle-icon me-2">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="">
                                    <path d="M10.0003 18.3337C14.6027 18.3337 18.3337 14.6027 18.3337 10.0003C18.3337 5.39795 14.6027 1.66699 10.0003 1.66699C5.39795 1.66699 1.66699 5.39795 1.66699 10.0003C1.66699 14.6027 5.39795 18.3337 10.0003 18.3337Z" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M6.66699 10H13.3337" stroke="#FC8019" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </g>
                            </svg>
                        </span>
                        <h3  class="m-0 poppins">${group.name}</h3>
                        </div>
                    </div>
                    <div class="group-options" style="display:${
                        index === 0 ? "block" : "none"
                    };">
                        ${optionsHtml}
                    </div>
                    <div class="modifier-error text-danger" style="display:none;"></div>
                </div>
            `;
        });
    } else {
        modifierHtml = `<p class="text-muted">No addons available</p>`;
    }
    $("#modifier-container").html(modifierHtml);

    $("#variation-product-modal").modal("show");
    updateTotalPrice();
}
// Collapse/Expand on SVG click
$(document).on("click", ".svg-toggle", function () {
    const groupDiv = $(this).closest(".addons-section").find(".group-options");
    groupDiv.slideToggle(); // smooth toggle

    // Optional: rotate SVG for UX
    $(this).find("svg").toggleClass("rotated");
});

// Handle variation selection
$(document).on("change", ".variation-item .variation-option", function () {
    $(".variation-item .variation-option").not(this).prop("checked", false);

    $(".variation-item").removeClass("active");

    if ($(this).is(":checked")) {
        $(this).closest(".variation-item").addClass("active");
    }

    let $variationItem = $(this).closest(".variation-item");
    let variationId = $variationItem.data("variation-id");

    let selectedPrice = $variationItem.find(".price").data("price");

    selectedProduct.selectedVariation = {
        variation_id: variationId,
        price: selectedPrice,
    };

    updateTotalPrice();
});

//check multiple select
$(document).on("change", ".modifier-option", function () {
    const groupId = $(this).data("group-id");
    const isMultiple = $(this).data("multiple");

    if (!isMultiple) {
        $(`.modifier-option[data-group-id="${groupId}"]`)
            .not(this)
            .prop("checked", false);
    }
    updateTotalPrice();
});

//variation modal qty
let quantity = 1;
$(document).on("click", ".variationPlus", function () {
    let input = $(this).siblings(".variationInputQTy");
    let val = parseInt(input.val()) || 1;
    input.val(val + 1).trigger("input");
});

$(document).on("click", ".variationMinus", function () {
    let input = $(this).siblings(".variationInputQTy");
    let val = parseInt(input.val()) || 1;
    if (val > 1) {
        input.val(val - 1).trigger("input");
    }
});

// When input changes manually
$(document).on("input", ".variationInputQTy", function () {
    let val = parseInt($(this).val()) || 1;
    if (val < 1) val = 1;
    $(this).val(val);

    quantity = val;
    updateTotalPrice();
});

function calculateTotalWithoutQty() {
    let total = 0;

    // Variation price
    if (selectedProduct.selectedVariation) {
        total += parseFloat(selectedProduct.selectedVariation.price || 0);
    }

    // Modifiers
    selectedProduct.selectedModifiers = [];
    $(".modifier-option:checked").each(function () {
        let price = parseFloat($(this).data("price") || 0);
        let optionId = $(this).data("option-id");
        let optionName = $(this).data("name");
        let modifierId = $(this).data("modifier-id");
        total += price;

        selectedProduct.selectedModifiers.push({
            option_id: optionId,
            name: optionName,
            modifier_id: modifierId,
            price: price,
        });
    });

    return total;
}

//variation and mdifier total price
function updateTotalPrice() {
    let total = calculateTotalWithoutQty();

    let qty = parseInt($(".variationInputQTy").val()) || 1;
    total = total * qty;

    $(".add-to-cart").text(`Add to Cart  ${currencyFormat(total)}`);
}

function validateModifiers() {
    let isValid = true;

    $(".addons-section").each(function () {
        const $section = $(this);
        const required = $section.data("required");
        const checked = $section.find("input.modifier-option:checked").length;
        const $errorDiv = $section.find(".modifier-error");

        if (required && checked === 0) {
            isValid = false;
            $errorDiv.text("Please select the modifier option").show();
        } else {
            $errorDiv.text("").hide();
        }
    });

    return isValid;
}

let savingLoader =
        '<div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
    $product_modal_reload = $("#variationAddCartmodal");
($product_modal_reload.initFormValidation(),
    // item modal action
    $("#variationAddCartmodal").on("submit", function (e) {
        e.preventDefault();
        if (!validateModifiers()) {
            // Scroll to first error if present
            const firstError = $(".modifier-error:visible").first();
            if (firstError.length) {
                $("html, body").animate(
                    { scrollTop: firstError.offset().top - 100 },
                    300,
                );
            }
            return false; // stop submission
        }
        if (!$product_modal_reload.valid()) return;

        let t = $(this).find(".submit-btn"),
            a = t.html();
        let url = $(this).data("route");
        let quantity = parseFloat($(".variationInputQTy").val());
        $(".variationInputQTy").val("1");
        let price = calculateTotalWithoutQty();
        $product_modal_reload.valid() &&
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    type: "onlineSale",
                    id: selectedProduct.productId,
                    name: selectedProduct.productName,
                    image: selectedProduct.image,
                    variation_id:
                        selectedProduct.selectedVariation?.variation_id || null,
                    modifiers: selectedProduct.selectedModifiers,
                    price: price,
                    quantity: quantity,
                },
                beforeSend: function () {
                    t.html(savingLoader).attr("disabled", !0);
                },
                success: function (response) {
                    t.html(a).removeClass("disabled").attr("disabled", !1);

                    if (response.success) {
                        updateHeaderCartCount();
                        $("#variation-product-modal").modal("hide");
                        toastr.success(response.message);
                    } else {
                        toastr.error(
                            response.message ||
                                "Failed to add product to cart.",
                        );
                    }
                },
                error: function (xhr) {
                    toastr.error(
                        "An error occurred while adding product to cart.",
                    );
                },
            });
    }));

//single product add to cart
$(document).on("click", ".single-product", function () {
    addItemToCart($(this));
});

function addItemToCart(element) {
    let url = element.data("route");
    let productId = element.data("product-id");
    let productName = element.data("product-name");
    let productImage = element.data("image");
    let salesPrice = element.data("sales-price");

    $.ajax({
        url: url,
        type: "POST",
        data: {
            type: "onlineSale",
            id: productId,
            name: productName,
            image: productImage,
            price: salesPrice,
            quantity: 1,
        },
        success: function (response) {
            if (response.success) {
                updateHeaderCartCount();
                toastr.success(response.message);
            } else {
                toastr.error(response.message);
            }
        },
        error: function (xhr) {
            console.error("Error:", xhr.responseText);
        },
    });
}

$(document).ready(function () {
    updateCartProductCount();
});

function updateCartProductCount() {
    let productCount = $("#cart-list").find("tr.product-cart-tr").length;
    $(".totalCartProduct").text(productCount);
}

$(document).on("click", ".plus-btn, .minus-btn", function (e) {
    e.preventDefault();

    let $row = $(this).closest("tr");
    let rowId = $row.data("row_id");
    let updateRoute = $row.data("update_route");
    let $qtyInput = $row.find(".cart-qty");

    let newQty = parseFloat($qtyInput.val());
    if ($(this).hasClass("plus-btn")) newQty++;
    else newQty = Math.max(1, newQty - 1);
    $qtyInput.val(newQty);
    let price = parseFloat($row.data("cart_price"));
    let newSubtotal = newQty * price;
    // UPDATE SUBTOTAL CELL IMMEDIATELY
    $row.find(".cart-row-subtotal").text(currencyFormat(newSubtotal));

    updateCart(rowId, newQty, updateRoute);
});

// Cart quantity input field change event
$(document).on("change", ".cart-qty", function () {
    let $row = $(this).closest("tr");
    let rowId = $row.data("row_id");
    let updateRoute = $row.data("update_route");
    let newQty = parseFloat($(this).val());

    // Retrieve the cart price
    let currentPrice = $row.data("cart_price");
    if (isNaN(currentPrice) || currentPrice < 0) {
        toastr.error("Price can not be negative.");
        return;
    }
    let newSubtotal = newQty * currentPrice;
    $row.find(".cart-row-subtotal").text(currencyFormat(newSubtotal));

    // Ensure quantity does not go below 0
    if (newQty >= 0) {
        updateCart(rowId, newQty, updateRoute);
    }
});

// Remove item from the cart
$(document).on("click", ".singleCartRemove", function (e) {
    e.preventDefault();
    var $row = $(this).closest("tr");
    var rowId = $row.data("row_id");
    var removeRoute = $row.data("remove_route");

    $.ajax({
        url: removeRoute,
        type: "DELETE",
        data: {
            rowId: rowId,
        },
        success: function (response) {
            if (response.success) {
                $row.remove();
                calTotalAmount();
                updateHeaderCartCount();
                if ($("#cart-list tr.product-cart-tr").length === 0) {
                    $(".hideSummary").hide();
                    $("#cart-list").html(
                        '<tr><td colspan="5">Your cart is empty.</td></tr>',
                    );
                }
            } else {
                toastr.error(response.message || "Failed to remove item");
            }
        },
        error: function () {
            toastr.error("Error removing item from cart");
        },
    });
});

// Function to update cart item with the new quantity
function updateCart(rowId, qty, updateRoute) {
    $.ajax({
        url: updateRoute,
        type: "POST",
        data: {
            rowId: rowId,
            qty: qty,
        },
        success: function (response) {
            if (response.success) {
                calTotalAmount();
                updateHeaderCartCount();
            } else {
                toastr.error(response.message || "Failed to update cart");
            }
        },
    });
}

//calculation part

// Function to calculate
function calTotalAmount() {
    let subtotal = 0;

    let totalItem = $("#cart-list tr.product-cart-tr").length;
    if (totalItem > 0) {
        $("#cart-list tr.product-cart-tr").each(function () {
            let qty = getNumericValue($(this).find(".cart-qty").val()) || 0;
            let price = $(this).data("cart_price") || 0;

            subtotal += qty * price;
        });
    }
    // Show subtotal
    $("#sub_total").text(currencyFormat(subtotal));
    $("#total_item_count").text(totalItem);
    //vat
    let vatRate = parseFloat($(".vatOnSale").data("vat-sale")) || 0;
    let vatAmount = subtotal * (vatRate / 100);
    $(".vatAmountValue").text(currencyFormat(vatAmount.toFixed(2)));
    $(".vatAmountValue").val(formattedAmount(vatAmount, 2));

    //discount calculation
    let discountAmount = Number($(".discount").data("value"));
    let discountType = $(".discount").data("type");

    let discount = 0;
    if (discountType === "percentage") {
        discount = (subtotal * discountAmount) / 100;
        $(".discountAmountShow").text(discount + "%");
    } else if (discountType === "flat") {
        discount = discountAmount;
        $(".discountAmountShow").text(currencyFormat(discount));
    }

    // Total Amount
    let total_amount = subtotal - discount + vatAmount;
    $(".totalAmmount").val(formattedAmount(total_amount, 2));
    $(".totalAmmount").text(currencyFormat(total_amount.toFixed(2)));
}

calTotalAmount();

$(document).ready(function () {
    let firstBtn = $(".option-btn").first();

    if (firstBtn.length) {
        let firstType = firstBtn.data("type");
        localStorage.setItem("selected_order_type", firstType);
    }
});

$(document).on("click", ".option-btn", function () {
    let selectedType = $(this).data("type");
    // Save to localStorage
    localStorage.setItem("selected_order_type", selectedType);

    // Add active class
    $(".option-btn").removeClass("active");
    $(this).addClass("active");
});

$(document).on("input", ".notesValue", function () {
    let notes_value = $(this).val();
    localStorage.setItem("notes_value", notes_value);
});
