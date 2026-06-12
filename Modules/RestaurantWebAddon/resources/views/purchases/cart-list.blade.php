@foreach($cart_contents as $cart)
    <tr class="product-cart-tr" data-row_id="{{ $cart->rowId }}" data-update_route="{{ route('business.carts.update', $cart->rowId) }}" data-destroy_route="{{ route('business.carts.destroy', $cart->rowId) }}">
        <td>1</td>
        <td>{{ $cart->name }}</td>
        <td>
            <div class="d-flex align-items-center justify-content-center">
                <select name="unit_id" class="form-select cart-unit-select">
                    @foreach ($units as $unit)
                    <option {{ $cart->options->unit_id == $unit->id ? 'selected' : '' }} value="{{ $unit->id }}">{{ $unit->unitName }}</option>
                    @endforeach
                </select>
            </div>
        </td>
        <td>
              <div class="d-flex align-items-center justify-content-center gap-2">
            <button class="incre-decre minus-btn">
                <i class="fas fa-minus icon"></i>
            </button>
            <input type="number"  value="{{ $cart->qty }}" class="dynamic-width h cart-qty" placeholder="0" aria-invalid="false">
            <button class="incre-decre plus-btn">
                <i class="fas fa-plus icon"></i>
            </button>
        </div>
        </td>
        <td><input type="number" class="cart-unit-price unitPrice" value="{{ $cart->price }}"></td>
        <td class="cart-subtotal">{{ currency_format($cart->subtotal, currency: business_currency()) }}</td>
        <td><span class="cart-remove-btn remove-cart">✕</span></td>
    </tr>
@endforeach
