"use strict";
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

// Profile Image Preview Start
$(document).ready(function () {
    const uploadBox = $(".upload-box");
    const imageInput = uploadBox.find(".image-input");
    const previewArea = uploadBox.find(".preview-area");
    const uploadText = uploadBox.find(".upload-text");

    function showImagePreview(file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            previewArea.html("");
            const img = $("<img>", {
                src: e.target.result,
                class: "profile-img",
                css: {
                    height: "60px",
                    width: "60px",
                },
            });
            previewArea.append(img);
        };
        reader.readAsDataURL(file);
    }

    previewArea.on("click", function () {
        imageInput.trigger("click");
    });

    imageInput.on("change", function () {
        if (this.files && this.files[0]) {
            showImagePreview(this.files[0]);
            uploadText.hide();
        }
    });

    // Drag & Drop //
    uploadBox.on("dragover", function (e) {
        e.preventDefault();
        uploadBox.addClass("drag-over");
    });
    uploadBox.on("dragleave", function (e) {
        e.preventDefault();
        uploadBox.removeClass("drag-over");
    });
    uploadBox.on("drop", function (e) {
        e.preventDefault();
        uploadBox.removeClass("drag-over");

        const files = e.originalEvent.dataTransfer.files;
        if (files && files[0]) {
            imageInput[0].files = files;
            showImagePreview(files[0]);
            uploadText.hide();
        }
    });
});
// Profile Imge Preview End

// Review modal Start
$(document).ready(function () {
    const stars = $("#rating-stars .star");
    const ratingInput = $("#rating-value");

    stars.on("click", function () {
        let rating = $(this).data("value");

        ratingInput.val(rating);
        stars.removeClass("filled");

        stars.each(function (index) {
            if (index < rating) {
                $(this).addClass("filled");
            }
        });
    });
});

$(document).on("click", ".get-product-id", function () {
    let productId = $(this).data("product-id");
    $("#review-product-id").val(productId);
});
// Review modal End

// Review Edit Start
$(document).on("click", ".review-edit-btn", function () {
    let url = $(this).data("url");
    let rating = $(this).data("rating");
    let review = $(this).data("review");

    $("#rating").val(rating);
    $("#review").val(review);
    $("#reviewUpdateForm").attr("action", url);

    highlightStars(rating);
});

$(document).on("click", ".rating-stars .star", function () {
    let rating = $(this).data("value");

    $("#rating").val(rating);
    highlightStars(rating);
});

function highlightStars(rating) {
    $(".rating-stars .star").each(function () {
        let value = $(this).data("value");

        if (value <= rating) {
            $(this).addClass("filled");
        } else {
            $(this).removeClass("filled");
        }
    });
}
// Review Edit End

let redirectAfterLogin = "";

$(document).on(
    "click",
    ".registerSignInRedirect, .placeOrderRedirect, .reservationLoginRedirect, .mobileReservationRedirect, .mobileProfileRedirect, .mobileOrderRedirect",
    function () {
        redirectAfterLogin = $(this).data("redirect-url");
        $("#login_redirect_url").val(redirectAfterLogin);
    },
);

$(".canvasHide").on("click", function () {
    let offcanvas = bootstrap.Offcanvas.getInstance(
        document.getElementById("offcanvasRight"),
    );
    if (offcanvas) {
        offcanvas.hide();
    }
});

// Business SignUp Start
$(document).ready(function () {
    // sign up form
    let $online_sign_up_form = $(".online_sign_up_form");
    $online_sign_up_form.initFormValidation();

    $(document).on("submit", ".online_sign_up_form", function (e) {
        e.preventDefault();

        let t = $(this).find(".submit-btn"),
            a = t.html();

        if ($online_sign_up_form.valid()) {
            $.ajax({
                type: "POST",
                url: this.action,
                data: new FormData(this),
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    t.html($savingLoader) // Show loader
                        .addClass("disabled")
                        .attr("disabled", true);
                },
                success: function (response) {
                    // Handle success response
                    $("#online-registration-modal").modal("hide");
                    $("#onlineDynamicEmail").text(response.email);
                    $("#onlineVerifymodal").modal("show");
                    startCountdown(response.otp_expiration);
                },
                error: function (e) {
                    // Handle error response
                    showInputErrors(e.responseJSON);
                    Notify("error", e);
                },
                complete: function () {
                    t.html(a).removeClass("disabled").attr("disabled", false);
                },
            });
        }
    });

    // OTP input field--------------------->
    const pinInputs = document.querySelectorAll(".pin-input");

    pinInputs.forEach((inputField, index) => {
        inputField.addEventListener("input", () => {
            inputField.value = inputField.value
                .replace(/[^0-9]/g, "")
                .slice(0, 1);

            if (inputField.value && index < pinInputs.length - 1) {
                pinInputs[index + 1].focus();
            }
        });

        inputField.addEventListener("keydown", (e) => {
            if (e.key === "Backspace" && !inputField.value && index > 0) {
                pinInputs[index - 1].focus();
            }
        });

        inputField.addEventListener("paste", (e) => {
            e.preventDefault();
        });
    });

    function showInputErrors(e) {
        if (e.errors !== undefined) {
            $.each(e.errors, function (field, message) {
                $("#" + field + "-error").remove();

                let errorLabel = `
                    <label id="${field}-error" class="error" for="${field}">${message}</label>
                `;

                $("#" + field)
                    .parents()
                    .hasClass("form-check")
                    ? $("#" + field)
                          .parents()
                          .find(".form-check")
                          .append(errorLabel)
                    : $("#" + field)
                          .addClass("error")
                          .after(errorLabel);
            });
        }
    }

    function ajaxSuccess(response, Notify) {
        if (response.redirect) {
            if (response.message) {
                window.sessionStorage.hasPreviousMessage = true;
                window.sessionStorage.previousMessage =
                    response.message ?? null;
            }

            location.href = response.redirect;
        } else if (response.message) {
            Notify("success", response);
        }
    }

    // Verify OTP submission
    let $online_verify_form = $(".online_verify_form");
    $online_verify_form.initFormValidation();

    $(document).on("submit", ".online_verify_form", function (e) {
        e.preventDefault();

        let t = $(this).find(".submit-btn"),
            a = t.html();

        const email = $("#onlineDynamicEmail").text();

        // Get the OTP input values from the form
        const otpInputs = $(this).find(".otp-input");
        let otpValues = otpInputs
            .map(function () {
                return $(this).val();
            })
            .get()
            .join("");

        // Validate OTP and form before submitting
        if ($online_verify_form.valid()) {
            let formData = new FormData(this);
            formData.append("email", email);
            formData.append("otp", otpValues);
            formData.append("redirect_url", redirectAfterLogin);

            $.ajax({
                type: "POST",
                url: this.action,
                data: formData,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    t.html($savingLoader)
                        .addClass("disabled")
                        .attr("disabled", true);
                },
                success: function (response) {
                    t.html(a).removeClass("disabled").attr("disabled", false);

                    // Hide OTP verification modal
                    $("#onlineVerifymodal").modal("hide");

                    // Check for successful OTP submission and show success modal
                    if (response.redirect ?? false) {
                        window.sessionStorage.hasPreviousMessage = true;
                        window.sessionStorage.previousMessage =
                            response.message || "Operation successfully.";
                        location.href = response.redirect;
                    } else {
                        // Show the success modal after OTP verification
                        $("#successmodal").modal("show");
                    }
                },
                error: function (response) {
                    t.html(a).removeClass("disabled").attr("disabled", false);
                    toastr.error(
                        response.responseJSON.message || "An error occurred.",
                    );
                },
            });
        } else {
            toastr.error("Please enter all OTP digits.");
        }
    });
});

// OTP countdown------------------->
let countdownInterval;
function startCountdown(timeLeft) {
    const countdownElement = $("#onlineCountdown");
    const resendButton = $("#online-otp-resend");

    // Function to format time as MM:SS
    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        return `${String(minutes).padStart(2, "0")}:${String(
            remainingSeconds,
        ).padStart(2, "0")}`;
    }

    // Clear any existing countdown interval
    if (countdownInterval) {
        clearInterval(countdownInterval);
    }

    // Initialize countdown display
    countdownElement.text(formatTime(timeLeft));
    resendButton.addClass("disabled").attr("disabled", true); // Disable the button during countdown

    // Start the new countdown interval
    countdownInterval = setInterval(() => {
        timeLeft--;

        // Update the countdown text
        countdownElement.text(formatTime(timeLeft));

        // Stop the countdown when timeLeft reaches zero
        if (timeLeft <= 0) {
            clearInterval(countdownInterval);
            countdownElement.text("00:00");

            // Enable the resend button
            resendButton.removeClass("disabled").removeAttr("disabled");
        }
    }, 1000);
}

// Resend OTP
$("#online-otp-resend").on("click", function () {
    const resendButton = $(this);

    // Prevent action if the button is disabled
    if (resendButton.hasClass("disabled")) {
        return;
    }

    const route = resendButton.data("route");
    const originalText = resendButton.text();
    const email = $("#onlineDynamicEmail").text();

    // Ensure email is available
    if (!email) {
        Notify("error", "Email is missing. Please try again.");
        return;
    }

    // Temporarily disable the button during the request
    resendButton.text("Sending...").addClass("disabled").attr("disabled", true);

    $.ajax({
        type: "POST",
        url: route,
        data: { email: email },
        dataType: "json",
        success: function (response) {
            resendButton
                .text(originalText)
                .addClass("disabled")
                .attr("disabled", true);
            startCountdown(response.otp_expiration);
        },
        error: function (e) {
            resendButton
                .text(originalText)
                .removeClass("disabled")
                .removeAttr("disabled");
        },
    });
});
// Business SignUp End

// Blogs Filter Start
$(document).ready(function () {
    function renderBlogs(blogs) {
        let restaurantSlug = $("#currentRestaurantSlug").val();
        let baseUrl = $("#baseUrl").val();

        $("#blogs-container").html("");

        if (blogs.length === 0) {
            $("#blogs-container").html(
                '<h3 class="text-center text-danger">No Blogs Found!</h3>',
            );
            return;
        }

        blogs.forEach((blog) => {
            let createdAt = new Date(blog.created_at).toLocaleDateString(
                "en-US",
                {
                    year: "numeric",
                    month: "short",
                    day: "2-digit",
                },
            );

            let image = blog.image
                ? `${baseUrl}/${blog.image}`
                : `${baseUrl}/assets/img/icon/no-image.svg`;
            let blogUrl = `/restaurant/${restaurantSlug}/blogs/${blog.slug}`;

            $("#blogs-container").append(`
                <div class="col-md-6">
                    <div class="blog-card">
                        <a href="${blogUrl}" class="card-thumb">
                            <img src="${image}" alt="">
                        </a>
                        <div class="card-description">
                            <div class="author-date">
                                <a href="${blogUrl}" class="date">
                                    <span><i><img src="${baseUrl}/assets/online/images/icons/calendar.svg" alt=""></i></span>
                                    ${createdAt}
                                </a>
                                <a href="javascript:void(0);" class="author">
                                    <span><i><img src="${baseUrl}/assets/online/images/icons/user.svg" alt=""></i></span>
                                    ${blog.user?.name ?? ""}
                                </a>
                            </div>
                            <a href="${blogUrl}">
                                <h3 class="post-title">${blog.title}</h3>
                            </a>
                            <a href="${blogUrl}" class="link"><i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            `);
        });
    }

    function fetchFilteredBlogs(search = null, tag = null) {
        let route = $("#blog-search-form").attr("action");

        $.ajax({
            url: route,
            type: "POST",
            data: { search: search, tag: tag },
            success: function (response) {
                renderBlogs(response.blogs.data);
            },
        });
    }

    // Search input
    $(document).on("keyup", ".blog-search-input", function () {
        let search = $(this).val();
        let selectedTag =
            $(".tags-btn.blogs-tag-btn-selected").data("tag") ?? null;
        fetchFilteredBlogs(search, selectedTag);
    });

    // Tag click
    $(document).on("click", ".tags-btn", function () {
        $(".tags-btn").removeClass("blogs-tag-btn-selected");
        $(this).addClass("blogs-tag-btn-selected");

        let selectedTag = $(this).data("tag");
        let search = $(".blog-search-input").val();
        fetchFilteredBlogs(search, selectedTag);
    });
});
// Blogs Filter End

// Coupon edit form
$(document).on("click", ".billingAddress-edit-btn", function () {
    var url = $(this).data("url");
    var name = $(this).data("name");
    var phone = $(this).data("phone");
    var email = $(this).data("email");
    var address = $(this).data("address");
    var city = $(this).data("city");
    var postcode = $(this).data("postcode");

    $("#billing_address_name").val(name);
    $("#billing_address_phone").val(phone);
    $("#billing_address_email").val(email);
    $("#billing_address_address").val(address);
    $("#billing_address_city").val(city);
    $("#billing_address_postcode").val(postcode);

    $(".billingAdressUpdateForm").attr("action", url);
});

function updateHeaderCartCount() {
    var url = $("#cartHeaderCount").val();
    $.ajax({
        url: url,
        type: "GET",
        success: function (data) {
            $(".online-cart-count").text(data.cart_count);
        },
        error: function () {
            console.error("Failed to fetch cart count.");
        },
    });
}

// Initial load
$(document).ready(function () {
    updateHeaderCartCount();
});

$("#offcanvasRight").on("show.bs.offcanvas", function () {
    var url = $("#cartItemGet").val();
    $.ajax({
        url: url,
        method: "GET",
        success: function (response) {
            let $cartList = $("#cart-list");
            $cartList.empty();
            // Convert object to array
            let items = Object.values(response.items);

            if (items.length === 0) {
                $(".hideSummary").hide();
                $cartList.append(
                    '<tr><td colspan="5">Your cart is empty.</td></tr>',
                );
                return;
            }
            $(".hideSummary").show();

            items.forEach(function (item) {
                let price = parseFloat(item.price);
                let subtotal = price * item.qty;

                let modifiersGroupOptionName = "";
                if (
                    Array.isArray(item.options.modifiers) &&
                    item.options.modifiers.length > 0
                ) {
                    modifiersGroupOptionName = item.options.modifiers
                        .map((mod) => mod.name)
                        .join("<br>");
                }

                let row = `
                    <tr class="product-cart-tr"
                     data-row_id="${item.rowId}"
                     data-cart_price="${price}"
                     data-update_route="${response.updateRoute}"
                     data-remove_route="${response.removeRoute}">
                        <td class="text-start">
                            <div class="burgar-item">
                                <div class="left-side">
                                    <img src="${item.options.image}" alt="">
                                </div>
                                <div class="right-side">
                                    <h5>${item.name}</h5>
                                     <span>${modifiersGroupOptionName}</span>
                                </div>
                            </div>
                        </td>
                        <td class="large-td">
                            <div class="d-flex gap-2 align-items-center justify-content-between">
                                <button class="incre-decre minus-btn">
                                    <i class="fas fa-minus icon"></i>
                                </button>
                                <input type="number" step="any" value="${item.qty}" class="dynamic-width cart-qty">
                                <button class="incre-decre plus-btn">
                                    <i class="fas fa-plus icon"></i>
                                </button>
                            </div>
                        </td>
                        <td class="cart-subtotal cart-price">${currencyFormat(price)}</td>
                        <td class="cart-subtotal cart-row-subtotal">${currencyFormat(subtotal)}</td>
                        <td>
                            <button class="x-btn singleCartRemove">
                                <img src="/assets/img/icon/Action.svg" alt="">
                            </button>
                        </td>
                    </tr>
                `;

                $cartList.append(row);
            });
            calTotalAmount();
        },
        error: function () {
            console.error("Failed to fetch cart items.");
        },
    });
});

$(".canvasHide").on("click", function () {
    let offcanvas = bootstrap.Offcanvas.getInstance(
        document.getElementById("offcanvasRight"),
    );
    if (offcanvas) {
        offcanvas.hide();
    }
});

$(document).on("click", ".my-order", function () {
    let url = $("#getOrderFilter").val();
    let status = $(this).data("status");

    $(".my-order").removeClass("active");
    $(this).addClass("active");

    $.ajax({
        url: url,
        type: "GET",
        data: { status: status },
        success: function (response) {
            $("#orderContent").html(response);
        },
        error: function () {
            alert("Something went wrong!");
        },
    });
});

getDashboardData();
function getDashboardData() {
    var url = $("#get-dashboard-data").val();
    $.ajax({
        type: "GET",
        url: url,
        dataType: "json",
        success: function (res) {
            $("#total_orders").text(res.total_orders);
            $("#total_pending").text(res.total_pending);
            $("#total_processing").text(res.total_processing);
            $("#total_delivered").text(res.total_delivered);
        },
    });
}

function loadTimeSlots() {
    let url = $("#getTimeSlots").val();
    let date = $("#reservationDate").val();
    let type = $("#reservationType").val();

    $.ajax({
        url: url,
        type: "GET",
        data: { date: date, type: type },
        success: function (response) {
            response = Array.isArray(response) ? response : [];

            let selectedDate = new Date($("#reservationDate").val());
            let today = new Date();
            today.setHours(0, 0, 0, 0);
            let isPastDate = selectedDate < today;

            //for no available
            if (response.length === 0) {
                $("#availableTimeArea").addClass("d-none");
                $("#noTimeArea").removeClass("d-none");
                $("#selectedTime").val("");
                return;
            }
            //for available
            $("#availableTimeArea").removeClass("d-none");
            $("#noTimeArea").addClass("d-none");

            let html = "";
            response.forEach((slot, index) => {
                let disabledClass = isPastDate ? "disabled-slot" : "";
                let activeClass = !isPastDate && index === 0 ? "active" : "";
                html += `<button class="time-btn ${activeClass} ${disabledClass}"
                data-time="${slot.value}">${slot.display}</button>`;
            });

            $("#timeSlotsArea").html(html);

            if (!isPastDate && response.length > 0) {
                $("#selectedTime").val(response[0].value);
            } else {
                $("#selectedTime").val("");
            }
        },
    });
}

$(document).on("click", ".time-btn", function (e) {
    e.preventDefault();
    $(".time-btn").removeClass("active");
    $(this).addClass("active");

    let selected = $(this).data("time");
    $("#selectedTime").val(selected);
});

$("#reservationDate, #reservationType").on("change", function () {
    loadTimeSlots();
});

loadTimeSlots();

$(document).on("click", ".maan-menu-star", function () {
    let url = $("#getReviewData").val();
    let productId = $(this).data("product-id");
    $("#reviewModalBody").html("<div class='text-center p-4'>Loading...</div>");

    $.ajax({
        url: url,
        type: "GET",
        data: { product_id: productId },
        success: function (html) {
            $("#reviewModalBody").html(html);
        },
        error: function () {
            $("#reviewModalBody").html(
                "<p class='text-danger p-4'>Failed to load reviews.</p>",
            );
        },
    });
});

$(document).ready(function () {
    $(".dateInput, .typeInput").on("change", function () {
        let date = $(".dateInput").val();
        let type = $(".typeInput").val();
        let url = $("#getTimeSlots").val();

        if (!date || !type) return;

        $.ajax({
            url: url,
            type: "GET",
            data: { date: date, type: type },

            success: function (response) {
                let $timeDropdown = $("#timeSelect");

                $timeDropdown.html(
                    '<option value="">Select time slot</option>',
                );

                if (response.length === 0) {
                    $("#reservation-alert-modal").modal("show");
                    $timeDropdown.niceSelect("update");
                    return;
                }
                response.forEach((slot) => {
                    $timeDropdown.append(
                        `<option value="${slot.value}">${slot.display}</option>`,
                    );
                });
                $timeDropdown.niceSelect("update");
            },
        });
    });
});

$(document).on("click", ".customer-invoice", function () {
    let url = $(this).data("url");
    let orderId = $(this).data("id");

    $("#invoiceModalBody").html('<p class="text-center py-4">Loading...</p>');

    $.ajax({
        url: url,
        type: "GET",
        data: { order_id: orderId },
        success: function (res) {
            $("#invoiceModalBody").html(res);
        },
        error: function () {
            $("#invoiceModalBody").html(
                '<p class="text-danger text-center">Failed to load invoice</p>',
            );
        },
    });
});

$(document).on("click", ".toggle-password", function () {
    const $input = $(this).siblings("input");
    const $icon = $(this).find("i");

    if ($input.attr("type") === "password") {
        $input.attr("type", "text");
        $icon.removeClass("fa-eye-slash").addClass("fa-eye");
    } else {
        $input.attr("type", "password");
        $icon.removeClass("fa-eye").addClass("fa-eye-slash");
    }
});

$(document).ajaxSuccess(function (event, xhr, settings, response) {
    if (response && !response.redirect) {
        $('.ajaxform[data-reset-on-success="true"]').each(function () {
            this.reset();
        });
    }
});

