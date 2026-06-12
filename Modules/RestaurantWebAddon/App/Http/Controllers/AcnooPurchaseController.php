<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Models\Unit;
use App\Models\Party;
use App\Models\Business;
use App\Models\Purchase;
use App\Models\Ingredient;
use App\Models\PaymentType;
use App\Models\Transaction;
use App\Helpers\HasUploader;
use Illuminate\Http\Request;
use App\Models\PurchaseDetails;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Gloudemans\Shoppingcart\Facades\Cart;
use Modules\RestaurantWebAddon\App\Exports\PurchaseExport;

class AcnooPurchaseController extends Controller
{
    use HasUploader;

    public function __construct()
    {
        $this->middleware('check.permission:purchases.view')->only('index');
        $this->middleware('check.permission:purchases.create')->only('create', 'store');
        $this->middleware('check.permission:purchases.update')->only('edit', 'update');
        $this->middleware('check.permission:purchases.delete')->only('destroy', 'deleteAll');
    }

    public function index(Request $request)
    {
        if (!auth()->user()) {
            return redirect()->back()->with('error', 'You have no permission to access.');
        }

        $purchases = Purchase::with('details', 'party', 'details.product', 'details.product.category', 'payment_type:id,name')
            ->where('business_id', auth()->user()->business_id)
            ->latest()
            ->paginate(20);

        return view('restaurantwebaddon::purchases.index', compact('purchases'));
    }

    public function acnooFilter(Request $request)
    {
        $business_id = auth()->user()->business_id;

        $purchases = Purchase::with('details', 'party', 'details.product', 'details.product.category', 'payment_type:id,name')
            ->where('business_id', $business_id)
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('invoiceNumber', 'like', '%' . $request->search . '%')
                        ->orWhereHas('party', function ($q) use ($request) {
                            $q->where('name', 'like', '%' . $request->search . '%');
                        })
                        ->orWhereHas('payment_type', function ($q) use ($request) {
                            $q->where('name', 'like', '%' . $request->search . '%');
                        });
                });
            })
            ->when($request->payment_status, function ($q) use ($request) {
                if ($request->payment_status === 'paid') {
                    $q->where('dueAmount', '=', 0);
                } elseif ($request->payment_status === 'partial') {
                    $q->whereColumn('dueAmount', '<', 'totalAmount')
                        ->where('dueAmount', '>', 0);
                } elseif ($request->payment_status === 'unpaid') {
                    $q->whereColumn('dueAmount', '=', 'totalAmount');
                }
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::purchases.datas', compact('purchases'))->render()
            ]);
        }

        return redirect(url()->previous());
    }

    public function create()
    {
        $business_id = auth()->user()->business_id;

        // Clears all cart items
        Cart::destroy();

        $suppliers = Party::where('type', 'supplier')
            ->where('business_id', $business_id)
            ->latest()
            ->get();

        $ingredients = Ingredient::where('business_id', $business_id)
            ->latest()
            ->get();

        $cart_contents = Cart::content()->filter(fn($item) => $item->options->type == 'purchase');
        $payment_types = PaymentType::where('business_id', $business_id)->whereStatus(1)->latest()->get();

        $units = Unit::where('business_id', $business_id)->where('status', 1)->latest()->get();

        return view('restaurantwebaddon::purchases.create', compact('suppliers', 'ingredients', 'cart_contents', 'payment_types', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'purchaseDate' => 'required|string',
            'party_id' => 'required|exists:parties,id',
            'payment_type_id' => 'nullable|exists:payment_types,id',
            'discountAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'discountPercentage' => 'nullable|numeric|min:0|max:99999999.99',
            'discount_type' => 'nullable|string',
            'tax_amount' => 'nullable|numeric|min:0|max:99999999.99',
            'tax_percentage' => 'nullable|numeric|min:0|max:99999999.99',
            'totalAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'dueAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'paidAmount' => 'nullable|numeric|min:0|max:99999999.99',
        ]);

        $business_id = auth()->user()->business_id;

        // Check each cart item for batch duplication in Stock table
        $carts = Cart::content()->filter(fn($item) => $item->options->type == 'purchase');

        if ($carts->count() < 1) {
            return response()->json(['message' => __('Cart is empty. Add items first!')], 400);
        }

        DB::beginTransaction();
        try {
            // Subtotal
            $subtotal = $carts->sum(fn($cartItem) => (float)$cartItem->subtotal);

            // Discount
            $vatAmount = $request->tax_amount ?? 0;
            $discountAmount = $request->discountAmount ?? 0;

            if ($discountAmount > $subtotal) {
                return response()->json(['message' => __('Discount cannot be more than subtotal with VAT!')], 400);
            }

            $totalAmount = $subtotal + $vatAmount - $discountAmount;

            $receiveAmount = $request->paidAmount ?? 0;
            $changeAmount = max($receiveAmount - $totalAmount, 0);
            $dueAmount = max($totalAmount - $receiveAmount, 0);
            $paidAmount = $receiveAmount - $changeAmount;

            // Party due update
            if ($dueAmount > 0) {
                $party = Party::findOrFail($request->party_id);
                $party->update(['due' => $party->due + $dueAmount]);
            }

            // Business balance update
            $business = Business::findOrFail($business_id);
            $business->update(['remainingShopBalance' => $business->remainingShopBalance - $paidAmount]);

            // Create Purchase
            $purchase = Purchase::create($request->except('business_id', 'user_id', 'tax_amount', 'tax_percentage', 'discountAmount', 'discountPercentage', 'totalAmount', 'paidAmount', 'dueAmount', 'payment_type_id', 'purchaseDate') + [
                'business_id' => $business_id,
                'user_id' => auth()->id(),
                'tax_amount' => $vatAmount ?? 0,
                'tax_percentage' => $request->tax_percentage ?? 0,
                'discountAmount' => $discountAmount ?? 0,
                'discountPercentage' => $request->discountPercentage ?? 0,
                'totalAmount' => $totalAmount ?? 0,
                'paidAmount' => $paidAmount ?? 0,
                'dueAmount' => $dueAmount ?? 0,
                'payment_type_id' => ($paidAmount > 0) ? $request->payment_type_id : null,
                'purchaseDate' => $request->purchaseDate ?? now(),
            ]);

            $purchase_details = [];

            // Insert Purchase Details and Create Stocks
            foreach ($carts as $cartItem) {

                // purchase detail
                $purchase_details[] = [
                    'purchase_id' => $purchase->id,
                    'ingredient_id' => $cartItem->id,
                    'quantities' => $cartItem->qty,
                    'unit_id' => $request->unit_id,
                    'unit_price' => $cartItem->price,
                ];
            }

            PurchaseDetails::insert($purchase_details);

            Transaction::create([
                'business_id' => $business_id,
                'purchase_id' => $purchase->id,
                'payment_type_id' => ($paidAmount > 0) ? $request->payment_type_id : null,
                'date' => $request->purchaseDate,
                'total_amount' => $purchase->totalAmount,
                'paid_amount' => $purchase->paidAmount,
                'due_amount' => $purchase->dueAmount,
                'type' => 'debit',
            ]);

            // Clear cart
            foreach ($carts as $cartItem) {
                Cart::remove($cartItem->rowId);
            }

            sendNotifyToUser($purchase->id, route('business.purchases.index', ['id' => $purchase->id]), __('New Purchase created.'), $business_id);

            DB::commit();

            return response()->json([
                'message' => __('Purchase created successfully.'),
                'redirect' => route('business.purchases.index'),
                'secondary_redirect_url' => route('business.purchases.invoice', $purchase->id),
            ]);
        } catch (\Exception) {
            DB::rollback();
            return response()->json(['message' => __('Something went wrong!')], 404);
        }
    }

    public function edit(string $id)
    {
        $business_id = auth()->user()->business_id;

        // Clears all cart items
        Cart::destroy();

        $purchase = Purchase::with('details', 'details.ingredient', 'details.unit', 'payment_type:id,name', 'party:id,name')
            ->where('business_id', $business_id)
            ->findOrFail($id);

        $suppliers = Party::where('type', 'Supplier')
            ->where('business_id', $business_id)
            ->latest()
            ->get();

        $ingredients = Ingredient::where('business_id', $business_id)
            ->latest()
            ->get();


        // Add purchase details to the cart
        foreach ($purchase->details as $detail) {
            // Add to cart
            Cart::add([
                'id' => $detail->ingredient_id,
                'name' => $detail->ingredient->name ?? '',
                'qty' => $detail->quantities,
                'price' => $detail->unit_price,
                'options' => [
                    'type' => 'purchase',
                    'unit_id' => $detail->unit_id,
                ],
            ]);
        }

        $cart_contents = Cart::content()->filter(fn($item) => $item->options->type == 'purchase');
        $payment_types = PaymentType::where('business_id', $business_id)->whereStatus(1)->latest()->get();
        $units = Unit::where('business_id', $business_id)->where('status', 1)->latest()->get();

        return view('restaurantwebaddon::purchases.edit', compact('purchase', 'suppliers', 'ingredients', 'cart_contents', 'units', 'payment_types'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'purchaseDate' => 'required|string',
            'party_id' => 'required|exists:parties,id',
            'payment_type_id' => 'nullable|exists:payment_types,id',
            'discountAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'discountPercentage' => 'nullable|numeric|min:0|max:99999999.99',
            'discount_type' => 'nullable|string',
            'tax_amount' => 'nullable|numeric|min:0|max:99999999.99',
            'tax_percentage' => 'nullable|numeric|min:0|max:99999999.99',
            'totalAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'dueAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'paidAmount' => 'nullable|numeric|min:0|max:99999999.99',
        ]);

        $business_id = auth()->user()->business_id;

        $carts = Cart::content()->filter(fn($item) => $item->options->type === 'purchase');

        if ($carts->count() < 1) {
            return response()->json(['message' => __('Cart is empty. Add items first!')], 400);
        }

        DB::beginTransaction();
        try {
            $purchase = Purchase::with('details')->findOrFail($id);

            // Calculate amounts
            $subtotal = $carts->sum(fn($cartItem) => (float)$cartItem->subtotal);

            // Discount
            $vatAmount = $request->tax_amount ?? 0;
            $discountAmount = $request->discountAmount ?? 0;

            if ($discountAmount > $subtotal) {
                return response()->json(['message' => __('Discount cannot be more than subtotal with VAT!')], 400);
            }


            // Total Amount
            $totalAmount = $subtotal + $vatAmount - $discountAmount;

            // Receive, Change, Due Amount Calculation
            $receiveAmount = $request->paidAmount ?? 0;
            $changeAmount = $receiveAmount > $totalAmount ? $receiveAmount - $totalAmount : 0;
            $dueAmount = max($totalAmount - $receiveAmount, 0);
            $paidAmount = $receiveAmount - $changeAmount;

            if ($purchase->dueAmount || $dueAmount) {
                $party = Party::findOrFail($request->party_id);
                $party->update([
                    'due' => $request->party_id == $purchase->party_id ? ($party->due - $purchase->dueAmount) + $dueAmount : $party->due + $dueAmount
                ]);

                if ($request->party_id != $purchase->party_id) {
                    $prev_party = Party::findOrFail($purchase->party_id);
                    $prev_party->update([
                        'due' => $prev_party->due - $purchase->dueAmount
                    ]);
                }
            }

            // Update business balance
            $business = Business::findOrFail(auth()->user()->business_id);
            $business->update([
                'remainingShopBalance' => $business->remainingShopBalance + $purchase->paidAmount - $paidAmount,
            ]);

            // Update purchase details
            $purchase->update($request->except('business_id', 'user_id', 'tax_amount', 'tax_percentage', 'discountAmount', 'discountPercentage', 'totalAmount', 'paidAmount', 'dueAmount', 'payment_type_id', 'purchaseDate') + [
                'business_id' => auth()->user()->business_id,
                'user_id' => auth()->id(),
                'tax_amount' => $vatAmount ?? 0,
                'tax_percentage' => $request->tax_percentage ?? 0,
                'discountAmount' => $discountAmount ?? 0,
                'discountPercentage' => $request->discountPercentage ?? 0,
                'totalAmount' => $totalAmount,
                'paidAmount' => $paidAmount,
                'dueAmount' => $dueAmount,
                'payment_type_id' => ($paidAmount > 0) ? $request->payment_type_id : null,
                'purchaseDate' => $request->purchaseDate ?? now(),
            ]);

            // Delete existing purchase details
            $purchase->details()->delete();

            // Insert updated purchase details and adjust stock
            $purchaseDetailsData = [];
            foreach ($carts as $cartItem) {

                $purchaseDetailsData[] = [
                    'purchase_id' => $purchase->id,
                    'ingredient_id' => $cartItem->id,
                    'quantities' => $cartItem->qty,
                    'unit_id' => $request->unit_id,
                    'unit_price' => $cartItem->price,
                ];
            }

            PurchaseDetails::insert($purchaseDetailsData);

            $transaction = Transaction::where('purchase_id', $purchase->id)->first();

            $transaction->update([
                'business_id' => $business_id,
                'purchase_id' => $purchase->id,
                'payment_type_id' => ($paidAmount > 0) ? $request->payment_type_id : null,
                'date' => $request->purchaseDate,
                'total_amount' => $purchase->totalAmount,
                'paid_amount' => $purchase->paidAmount,
                'due_amount' => $purchase->dueAmount,
                'type' => 'debit',
            ]);

            // Clear the cart
            foreach ($carts as $cartItem) {
                Cart::remove($cartItem->rowId);
            }

            sendNotifyToUser($purchase->id, route('business.purchases.index', ['id' => $purchase->id]), __('Purchase has been updated.'), $business_id);

            DB::commit();
            return response()->json([
                'message' => __('Purchase updated successfully.'),
                'redirect' => route('business.purchases.index'),
                'secondary_redirect_url' => route('business.purchases.invoice', $purchase->id),
            ]);
        } catch (\Exception) {
            DB::rollback();
            return response()->json(['message' => __('Something went wrong!')], 500);
        }
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $purchase = Purchase::with('details')->findOrFail($id);

            if ($purchase->party_id) {
                $party = Party::findOrFail($purchase->party_id);
                $party->update([
                    'due' => $party->due - $purchase->dueAmount
                ]);
            }

            $business = Business::findOrFail(auth()->user()->business_id);
            $business->update([
                'remainingShopBalance' => $business->remainingShopBalance + $purchase->paidAmount
            ]);

            sendNotifyToUser($purchase->id, route('business.purchases.index', ['id' => $purchase->id]), __('Purchase has been deleted.'), $purchase->business_id);

            $purchase->delete();

            // Clears all cart items
            Cart::destroy();

            DB::commit();

            return response()->json([
                'message' => __('Purchase deleted successfully.'),
                'redirect' => route('business.purchases.index')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => __('Something went wrong!')], 404);
        }
    }

    public function getInvoice(string $purchase_id)
    {
        $purchase = Purchase::with(
            'business:id,companyName,address,phoneNumber,vat_name,vat_no',
            'party:id,name',
            'user:id,name,role',
            'details:id,quantities,ingredient_id,unit_price,purchase_id',
            'details.ingredient:id,name',
            'payment_type:id,name'
        )
            ->findOrFail($purchase_id);

        return view('restaurantwebaddon::purchases.invoice', compact('purchase'));
    }

    public function showPurchaseCart()
    {
        $cart_contents = Cart::content()->filter(fn($item) => $item->options->type == 'purchase');
        $units = Unit::where('business_id', auth()->user()->business_id)->where('status', 1)->latest()->get();
        return view('restaurantwebaddon::purchases.cart-list', compact('cart_contents', 'units'));
    }

    public function getCartData()
    {
        $cart_contents = Cart::content()->filter(fn($item) => $item->options->type == 'purchase');
        return response()->json($cart_contents);
    }

    public function deleteAll(Request $request)
    {
        DB::beginTransaction();

        try {
            $purchases = Purchase::whereIn('id', $request->ids)->get();
            $business = Business::findOrFail(auth()->user()->business_id);

            foreach ($purchases as $purchase) {
                if ($purchase->party_id) {
                    $party = Party::findOrFail($purchase->party_id);
                    $party->update([
                        'due' => $party->due - $purchase->dueAmount
                    ]);
                }

                // Adjust business balance
                $business->update([
                    'remainingShopBalance' => $business->remainingShopBalance - $purchase->paidAmount
                ]);

                sendNotifyToUser($purchase->id, route('business.purchases.index', ['id' => $purchase->id]), __('Purchases has been deleted.'), $purchase->business_id);

                $purchase->delete();
            }

            // Clears all cart items
            Cart::destroy();

            DB::commit();

            return response()->json([
                'message' => __('Selected purchases deleted successfully.'),
                'redirect' => route('business.purchases.index')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => __('Something went wrong!')], 404);
        }
    }

    public function createSupplier(Request $request)
    {
        $request->validate([
            'phone' => 'nullable|max:20|' . Rule::unique('parties')->where('business_id', auth()->user()->business_id),
            'name' => 'required|string|max:255',
            'opening_balance' => 'nullable|numeric|min:0|max:99999999.99',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'type' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        Party::create($request->except('image', 'opening_balance') + [
            'opening_balance' => $request->opening_balance ?? 0,
            'due' => $request->opening_balance ?? 0,
            'business_id' => auth()->user()->business_id,
            'image' => $request->image ? $this->upload($request, 'image') : NULL,
        ]);

        return response()->json([
            'message'   => __('Supplier created successfully'),
            'redirect'  => url()->previous()
        ]);
    }

    public function createIngredient(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:ingredients,name,NULL,id,business_id,' . auth()->user()->business_id,
        ]);

        Ingredient::create($request->except('business_id') + [
            'business_id' => auth()->user()->business_id
        ]);

        return response()->json([
            'message' => __('Ingredient added successfully.'),
            'redirect' => url()->previous()
        ]);
    }

    public function generatePDF()
    {
        $purchases = Purchase::with('details', 'party', 'details.product', 'details.product.category', 'payment_type:id,name')
            ->where('business_id', auth()->user()->business_id)
            ->latest()
            ->get();

        $pdf = Pdf::loadView('restaurantwebaddon::purchases.pdf', compact('purchases'));
        return $pdf->download('purchase-list.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new PurchaseExport, 'purchase-list.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new PurchaseExport, 'purchase-list.csv');
    }
}
