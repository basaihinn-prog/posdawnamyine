@forelse($cart_contents as $cart)
<tr class="product-cart-tr" data-row_id="{{ $cart->rowId }}" data-update_route="{{ route('business.sale-carts.update', $cart->rowId) }}" data-destroy_route="{{ route('business.sale-carts.destroy', $cart->rowId) }}">
    <td class="text-start">
        {{ $cart->name }}
    </td>
    <td class="large-td ">
        <div class="d-flex gap-2 align-items-center">
            <button class="incre-decre minus-btn">
                <i class="fas fa-minus icon"></i>
            </button>
            <input type="number" step="any" value="{{ $cart->qty }}" class="dynamic-width cart-qty" placeholder="0">
            <button class="incre-decre plus-btn">
                <i class="fas fa-plus icon"></i>
            </button>
        </div>
    </td>
    <td class="cart-subtotal cart-price">{{ $cart->price }}</td>
    <td class="cart-subtotal">{{ currency_format($cart->subtotal, 'icon', 2, business_currency()) }}</td>
    <td>
        <button class="x-btn singleCartRemove">
            <img src="{{ asset('assets/img/icon/Action.svg') }}" alt="">
        </button>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="border-0 py-4">
        <div class="no-item-container">
            <img src="{{ asset('assets/images/icons/cart.svg') }}" alt="icon">
            <h3>{{ __('No items found') }}</h3>
        </div>
    </td>
</tr>
@endforelse



























