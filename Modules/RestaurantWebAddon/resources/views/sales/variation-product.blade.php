<div class="modal fade common-validation-modal" id="variation-product-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{ __('Item Details') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                 <div class="burger-card">
                <div class="burger-header">
                <img id="modalProductImage" src="" alt="Chicken Burger" class="burger-img">
                <div class="burger-info">
                    <h2 class="burger-title" id="variationProductName"></h2>
                    <p class="burger-subtitle" id="category-food"></p>
                </div>
                </div>

                <p class="burger-time">Preparation Time: <span id="preparation_time"></span></p>
                <p class="burger-desc" id="description"><a href="#" class="read-more">Read More...</a>
                </p>

                <form id="variationAddCartmodal" data-route="{{ route('business.sale-carts.store') }}">
                    @csrf
                <h3 class="variation-title poppins">Select Variations</h3>
                <div class="variation-box" id="variation-container">

                </div>
            </div>

        <div class="addons-container">
            <div id="modifier-container">

            </div>


            <!-- Footer Cart Section -->
        <div class="cart-footer">
        <div class="quantity-control">
            <button type="button" class="qty-btn variationMinus">−</button>
            <input type="number" class="qty-input variationInputQTy" value="1" min="1">
            <button type="button" class="qty-btn plus variationPlus">+</button>
        </div>
        <button class="add-to-cart submit-btn"></button>
        </div>
        </div>
    </form>
        </div>
    </div>
</div>
