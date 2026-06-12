<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Gloudemans\Shoppingcart\Exceptions\InvalidRowIDException;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart_contents = Cart::content()->filter(fn($item) => $item->options->type == 'sale');
        return view('business::sales.cart-list', compact('cart_contents'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'type' => 'nullable|string|in:sale,purchase',
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
            Cart::update($existingCartItem->rowId, [
                'qty' => $newQuantity,
            ]);
        } else {
            $mainItemData = [
                'id' => $request->id,
                'name' => $request->name,
                'qty'   => 1, // default qty
                'price' => 0,
                'options' => [
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
                $quantity = $request->input('qty', $cart->qty);
                $price    = $request->input('price', $cart->price);
                $unit_id  = $request->input('unit_id');

                Cart::update($id, [
                    'qty'   => $quantity,
                    'price' => $price,
                    'options' => array_merge($cart->options->toArray(), [
                        'unit_id' => $unit_id,
                    ]),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => __('Cart updated successfully!'),
                ]);
            }

            return response()->json(['success' => false, 'message' => __('Item not found in the cart')]);
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
