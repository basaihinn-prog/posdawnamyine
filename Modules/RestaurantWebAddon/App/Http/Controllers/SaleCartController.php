<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Gloudemans\Shoppingcart\Exceptions\InvalidRowIDException;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class SaleCartController extends Controller
{
    public function index()
    {
        $cart_contents = Cart::content()->filter(fn($item) => $item->options->type == 'sale');
        return view('restaurantwebaddon::sales.cart-list', compact('cart_contents'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'type' => 'nullable|string',
            'id' => 'required|integer',
            'name' => 'required|string',
        ]);

        // Search for existing item in the cart
        $existingCartItem = Cart::search(function ($cartItem) use ($request) {
            return $cartItem->id == $request->id && $cartItem->options->type == $request->type;
        })->first();

        if ($existingCartItem) {
            // Update the quantity of the existing item
            $newQuantity = $existingCartItem->qty + $request->quantity;
            $newPrice =  $request->price ?? 0;
            Cart::update($existingCartItem->rowId, [
                'qty' => $newQuantity,
                'price' => $newPrice,
            ]);
        } else {
            $mainItemData = [
                'id' => $request->id,
                'name' => $request->name,
                'qty'   => $request->quantity,
                'price' => $request->price,
                'options' => [
                    'variation_id' => $request->variation_id,
                    'modifiers' => $request->modifiers,
                    'type' => $request->type,
                ]
            ];

            Cart::add($mainItemData);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart successfully!'
        ]);
    }

    public function update(Request $request, string $id)
    {
        try {
            $cart = Cart::get($id);

            if ($cart) {
                $quantity = $request->input('qty');
                $price = $request->input('price'); // If sale

                if ($quantity >= 0) {
                    $updateData = ['qty' => $quantity];

                    if ($price !== null && $price >= 0) {
                        $updateData['price'] = $price;
                    }

                    // Update the cart
                    Cart::update($id, $updateData);

                    return response()->json([
                        'success' => true,
                        'message' => __('Quantity') .
                            ($price !== null ? __(' and price') : '') .
                            __(' updated successfully')
                    ]);
                } else {
                    return response()->json(['success' => false, 'message' => __('Enter a valid quantity')]);
                }
            } else {
                return response()->json(['success' => false, 'message' => __('Item not found in the cart')]);
            }
        } catch (InvalidRowIDException $e) {
            return response()->json(['success' => false, 'message' => __('The cart does not contain this item')]);
        }
    }

    public function destroy(string $id)
    {
        try {
            Cart::remove($id);
            return response()->json(['success' => true, 'message' => __('Item removed from cart')]);
        } catch (InvalidRowIDException $e) {
            return response()->json(['success' => false, 'message' => __('The cart does not contain this item')]);
        }
    }

    public function removeAllCart()
    {
        $carts = Cart::content();

        if ($carts->count() < 1) {
            return response()->json(['message' => __('Cart is empty. Add items first!')]);
        }

        Cart::destroy();

        $response = [
            'success' => true,
            'message' => __('All cart removed successfully!'),
        ];

        return response()->json($response);
    }
}
