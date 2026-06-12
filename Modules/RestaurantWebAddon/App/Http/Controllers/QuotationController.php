<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Party;
use App\Models\PaymentType;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuotationDetails;
use App\Models\Staff;
use App\Models\Table;
use App\Models\Tax;
use Barryvdh\DomPDF\Facade\Pdf;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RestaurantWebAddon\App\Exports\QuotationExport;

class QuotationController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:quotations.view')->only('index');
        $this->middleware('check.permission:quotations.create')->only('create', 'store');
        $this->middleware('check.permission:quotations.update')->only('edit', 'update', 'convertSale');
        $this->middleware('check.permission:quotations.delete')->only('destroy', 'deleteAll');
    }

    public function index()
    {
        $quotations = Quotation::with('party:id,name', 'payment_type:id,name')
            ->where('business_id', auth()->user()->business_id)
            ->latest()
            ->paginate(20);

        return view('restaurantwebaddon::quotations.index', compact('quotations'));
    }

    public function acnooFilter(Request $request)
    {
        $business_id = auth()->user()->business_id;

        $quotations = Quotation::with('party:id,name', 'payment_type:id,name')
            ->where('business_id', $business_id)
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('invoiceNumber', 'like', '%' . $request->search . '%')
                        ->orWhere('quotationDate', 'like', '%' . $request->search . '%')
                        ->orWhere('totalAmount', 'like', '%' . $request->search . '%')
                        ->orWhere('discountAmount', 'like', '%' . $request->search . '%')
                        ->orWhere('paidAmount', 'like', '%' . $request->search . '%')
                        ->orWhere('dueAmount', 'like', '%' . $request->search . '%')
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
                'data' => view('restaurantwebaddon::quotations.datas', compact('quotations'))->render()
            ]);
        }

        return redirect(url()->previous());
    }

    public function create(Request $request)
    {
        $business_id = auth()->user()->business_id;

        // Clears all cart items
        Cart::destroy();

        $products = Product::with('menu:id,name', 'category:id,categoryName', 'variations:product_id,id,name,price', 'modifiers:id,product_id,modifier_group_id,is_required,is_multiple', 'modifiers.modifier_group:id,name', 'modifiers.modifier_group.modifier_group_option:id,modifier_group_id,is_available,name,price')->where('business_id', $business_id)->latest()->get();
        $customers = Party::where('type', '!=', 'supplier')->where('business_id', $business_id)->latest()->get();
        $staffs = Staff::where('business_id', $business_id)->where('designation', 'waiter')->latest()->get();
        $tables = Table::where('business_id', $business_id)->latest()->get();
        $categories = Category::where('business_id', $business_id)->latest()->get();
        $payment_types = PaymentType::where('business_id', $business_id)->whereStatus(1)->latest()->get();
        $vat_on_sale = Tax::where('business_id', $business_id)->where('vat_on_sale', 1)->first();
        $cart_contents = Cart::content()->filter(fn($item) => $item->options->type == 'sale');

        return view('restaurantwebaddon::quotations.create', compact('customers', 'products', 'staffs', 'tables', 'categories', 'payment_types', 'vat_on_sale', 'cart_contents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'party_id' => 'nullable|exists:parties,id',
            'address_id' => 'nullable|exists:delivery_addresses,id',
            'tax_id' => 'nullable|exists:taxes,id',
            'coupon_id' => 'nullable|exists:coupons,id',
            'payment_type_id' => 'nullable|exists:payment_types,id',
            'discountAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'discountPercentage' => 'nullable|numeric',
            'coupon_amount' => 'nullable|numeric|min:0|max:99999999.99',
            'coupon_percentage' => 'nullable|numeric',
            'tax_amount' => 'nullable|numeric|min:0|max:99999999.99',
            'totalAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'paidAmount' => 'nullable|numeric|min:0|max:99999999.99',
        ]);

        $business_id = auth()->user()->business_id;

        $carts = Cart::content()->filter(fn($item) => $item->options->type == 'sale');

        if ($carts->count() < 1) {
            return response()->json(['message' => __('Cart is empty. Add items first!')], 400);
        }

        DB::beginTransaction();
        try {

            // Common Calculation
            $subtotal = $carts->sum(fn($item) => (float) $item->subtotal);
            $vat = Tax::find($request->vat_id);
            $vatAmount = $vat ? ($subtotal * $vat->rate) / 100 : 0;

            $discountAmount = $request->discountAmount ?? 0;
            if ($discountAmount > $subtotal) {
                return response()->json(['message' => __('Discount cannot be more than subtotal with VAT!')], 400);
            }

            $couponAmount = $request->coupon_amount ?? 0;
            $tipAmount = $request->tip ?? 0;
            $deliveryAmount = $request->delivery_charge ?? 0;
            $actualTotalAmount = ($subtotal - $discountAmount - $couponAmount) + $vatAmount + $tipAmount + $deliveryAmount;

            $paidAmount = $request->paidAmount ?? 0;
            $changeAmount = $paidAmount > $actualTotalAmount ? $paidAmount - $actualTotalAmount : 0;
            $dueAmount = max($actualTotalAmount - $paidAmount, 0);
            $paidAmount = $paidAmount - $changeAmount;

            // Save Quotation
            $quotation = Quotation::create([
                'business_id' => $business_id,
                'party_id' => $request->party_id,
                'address_id' => $request->address_id,
                'user_id' => auth()->id(),
                'tax_id' => $request->vat_id,
                'coupon_id' => $request->coupon_id,
                'payment_type_id' => ($paidAmount > 0) ? $request->payment_type_id : null,
                'discountAmount' => $discountAmount,
                'discountPercentage' => $request->discountPercentage ?? 0,
                'coupon_amount' => $couponAmount,
                'coupon_percentage' => $request->coupon_percentage ?? 0,
                'tax_amount' => $vatAmount,
                'totalAmount' => $actualTotalAmount,
                'paidAmount' => $paidAmount > $actualTotalAmount ? $actualTotalAmount : $paidAmount,
                'dueAmount' => $dueAmount,
                'quotationDate' => now(),
                'meta' => [
                    'tip' => $request->tip ?? 0,
                    'payment_method' => $paidAmount >= $actualTotalAmount ? "fullPayment" : "duePayment"
                ]
            ]);

            // QuotationDetails
            foreach ($carts as $cartItem) {
                $quotationDetail = QuotationDetails::create([
                    'quotation_id' => $quotation->id,
                    'product_id' => $cartItem->id,
                    'variation_id' => $cartItem->options->variation_id ?? null,
                    'price' => $cartItem->price,
                    'quantities' => $cartItem->qty,
                    'instructions' => $cartItem->options->instructions ?? null,
                ]);

                //QuotationDetailOptions
                if (!empty($cartItem->options['modifiers'])) {
                    $modifierData = [];
                    foreach ($cartItem->options['modifiers'] as $modifier) {
                        $modifierData[] = [
                            'quotation_detail_id' => $quotationDetail->id,
                            'option_id' => $modifier['option_id'],
                            'modifier_id' => $modifier['modifier_id'],
                        ];
                    }
                    if (!empty($modifierData)) {
                        DB::table('quotation_detail_options')->insert($modifierData);
                    }
                }
            }

            // Clear the cart
            foreach ($carts as $cartItem) {
                Cart::remove($cartItem->rowId);
            }

            sendNotifyToUser($quotation->id, route('business.quotations.index', ['id' => $quotation->id]), __('New quotation created.'), $business_id);

            DB::commit();
            return response()->json([
                'message' => __('Quotation saved successfully.'),
                'redirect' => route('business.quotations.index'),
                'secondary_redirect_url' => route('business.quotations.invoice', $quotation->id),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => __('Something went wrong!'), 'error' => $e->getMessage()], 500);
        }
    }

    public function edit(string $id)
    {
        $business_id = auth()->user()->business_id;

        // Clears all cart items
        Cart::destroy();

        $quotation = Quotation::with('user:id,name', 'coupon:id,discount,discount_type', 'party:id,name,email,phone,type', 'details', 'details.product:id,productName', 'details.detail_options:id,option_id,modifier_id')
            ->where('business_id', $business_id)
            ->findOrFail($id);

        $customers = Party::where('type', '!=', 'supplier')
            ->where('business_id', $business_id)
            ->latest()
            ->get();

        $products = Product::with(
            'menu:id,name',
            'category:id,categoryName',
            'variations:product_id,id,name,price',
            'modifiers:id,product_id,modifier_group_id,is_required,is_multiple',
            'modifiers.modifier_group:id,name',
            'modifiers.modifier_group.modifier_group_option:id,modifier_group_id,is_available,name,price'
        )
            ->where('business_id', $business_id)
            ->latest()
            ->get();

        $staffs = Staff::where('business_id', $business_id)
            ->where('designation', 'waiter')
            ->latest()
            ->get();

        $tables = Table::where('business_id', $business_id)
            ->latest()
            ->get();

        $categories = Category::where('business_id', $business_id)
            ->latest()
            ->get();
        $payment_types = PaymentType::where('business_id', $business_id)
            ->whereStatus(1)
            ->latest()
            ->get();

        $vat_on_sale = Tax::where('business_id', $business_id)
            ->where('vat_on_sale', 1)
            ->first();

        // Add sale details to the cart
        foreach ($quotation->details as $detail) {
            $modifiers = [];
            if (!empty($detail->detail_options)) {
                foreach ($detail->detail_options as $detail_option) {
                    $modifiers[] = [
                        'option_id'   => $detail_option->option_id,
                        'modifier_id' => $detail_option->modifier_id,
                    ];
                }
            }
            // Add to cart
            Cart::add([
                'id' => $detail->product_id,
                'name' => $detail->product->productName ?? '',
                'qty' => $detail->quantities,
                'price' => $detail->price ?? 0,
                'options' => [
                    'variation_id' => $detail->variation_id,
                    'modifiers' => $modifiers,
                    'type' => 'sale',
                ]
            ]);
        }

        $cart_contents = Cart::content()->filter(fn($item) => $item->options->type == 'sale');

        return view('restaurantwebaddon::quotations.edit', compact('quotation', 'customers', 'products', 'staffs', 'tables', 'categories', 'payment_types', 'vat_on_sale', 'cart_contents'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'party_id' => 'nullable|exists:parties,id',
            'address_id' => 'nullable|exists:delivery_addresses,id',
            'tax_id' => 'nullable|exists:taxes,id',
            'coupon_id' => 'nullable|exists:coupons,id',
            'payment_type_id' => 'nullable|exists:payment_types,id',
            'discountAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'discountPercentage' => 'nullable|numeric',
            'coupon_amount' => 'nullable|numeric|min:0|max:99999999.99',
            'coupon_percentage' => 'nullable|numeric',
            'tax_amount' => 'nullable|numeric|min:0|max:99999999.99',
            'totalAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'paidAmount' => 'nullable|numeric|min:0|max:99999999.99',
        ]);

        $business_id = auth()->user()->business_id;

        $carts = Cart::content()->filter(fn($item) => $item->options->type == 'sale');

        if ($carts->count() < 1) {
            return response()->json(['message' => __('Cart is empty. Add items first!')], 400);
        }

        DB::beginTransaction();
        try {
            $quotation = Quotation::where('business_id', $business_id)->findOrFail($id);

            // Calculation
            $subtotal = $carts->sum(fn($item) => (float) $item->subtotal);
            $vat = Tax::find($request->vat_id);
            $vatAmount = $vat ? ($subtotal * $vat->rate) / 100 : 0;

            $discountAmount = $request->discountAmount ?? 0;
            if ($discountAmount > $subtotal) {
                return response()->json(['message' => __('Discount cannot be more than subtotal with VAT!')], 400);
            }

            $couponAmount = $request->coupon_amount ?? 0;
            $tipAmount = $request->tip ?? 0;
            $deliveryAmount = $request->delivery_charge ?? 0;
            $actualTotalAmount = ($subtotal - $discountAmount - $couponAmount) + $vatAmount + $tipAmount + $deliveryAmount;

            $paidAmount = $request->paidAmount ?? 0;
            $changeAmount = $paidAmount > $actualTotalAmount ? $paidAmount - $actualTotalAmount : 0;
            $dueAmount = max($actualTotalAmount - $paidAmount, 0);
            $paidAmount = $paidAmount - $changeAmount;

            // Update Quotation
            $quotation->update([
                'party_id' => $request->party_id,
                'address_id' => $request->address_id,
                'user_id' => auth()->id(),
                'tax_id' => $request->vat_id,
                'coupon_id' => $request->coupon_id,
                'payment_type_id' => ($paidAmount > 0) ? $request->payment_type_id : null,
                'discountAmount' => $discountAmount,
                'discountPercentage' => $request->discountPercentage ?? 0,
                'coupon_amount' => $couponAmount,
                'coupon_percentage' => $request->coupon_percentage ?? 0,
                'tax_amount' => $vatAmount,
                'totalAmount' => $actualTotalAmount,
                'paidAmount' => $paidAmount,
                'dueAmount' => $dueAmount,
                'meta' => ['tip' => $tipAmount]
            ]);

            // remove old details
            $quotation->details()->delete();

            // insert into cart
            foreach ($carts as $cartItem) {
                $quotationDetail = QuotationDetails::create([
                    'quotation_id' => $quotation->id,
                    'product_id' => $cartItem->id,
                    'variation_id' => $cartItem->options->variation_id ?? null,
                    'price' => $cartItem->price,
                    'quantities' => $cartItem->qty,
                    'instructions' => $cartItem->options->instructions ?? null,
                ]);

                if (!empty($cartItem->options['modifiers'])) {
                    $modifierData = [];
                    foreach ($cartItem->options['modifiers'] as $modifier) {
                        $modifierData[] = [
                            'quotation_detail_id' => $quotationDetail->id,
                            'option_id' => $modifier['option_id'],
                            'modifier_id' => $modifier['modifier_id'],
                        ];
                    }
                    if (!empty($modifierData)) {
                        DB::table('quotation_detail_options')->insert($modifierData);
                    }
                }
            }

            // Cart clear
            foreach ($carts as $cartItem) {
                Cart::remove($cartItem->rowId);
            }

            sendNotifyToUser($quotation->id, route('business.quotations.index', ['id' => $quotation->id]), __('Quotation has been updated.'), $business_id);

            DB::commit();
            return response()->json([
                'message' => __('Quotation updated successfully.'),
                'redirect' => route('business.quotations.index'),
                'secondary_redirect_url' => route('business.quotations.invoice', $quotation->id),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => __('Something went wrong!'), 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        Quotation::where('id', $id)->delete();
        sendNotifyToUser(null, route('business.quotations.index'), __('Quotation has been deleted.'), auth()->user()->business_id);

        return response()->json([
            'message' => __('Quotation deleted successfully.'),
            'redirect' => route('business.quotations.index')
        ]);
    }

    public function deleteAll(Request $request)
    {
        $quotations = Quotation::whereIn('id', $request->ids)->get();

        foreach ($quotations as $quotation) {
            $quotation->delete();

            sendNotifyToUser(null, route('business.quotations.index'), __('Selected quotations have been deleted.'), auth()->user()->business_id);
        }

        return response()->json([
            'message' => __('Selected quotation deleted successfully.'),
            'redirect' => route('business.quotations.index')
        ]);
    }

    public function getInvoice(string $quotation_id)
    {
        $quotation = Quotation::with('user:id,name,role', 'party:id,name', 'business:id,phoneNumber,companyName,address,vat_name,vat_no', 'details:id,price,quantities,product_id,quotation_id', 'details.product:id,productName', 'details.detail_options:id,quotation_detail_id,option_id', 'details.detail_options.modifier_group_option:id,name,price', 'payment_type:id,name')
            ->where('business_id', auth()->user()->business_id)
            ->findOrFail($quotation_id);

        return view('restaurantwebaddon::quotations.invoice', compact('quotation'));
    }

    public function convertSale(string $id)
    {
        $business_id = auth()->user()->business_id;

        Cart::destroy();

        $quotation = Quotation::with('user:id,name', 'coupon:id,discount,discount_type', 'party:id,name,email,phone,type', 'details', 'details.product:id,productName', 'details.detail_options:id,option_id,modifier_id')
            ->where('business_id', $business_id)->findOrFail($id);

        $customers = Party::where('type', '!=', 'supplier')->where('business_id', $business_id)->latest()->get();
        $products = Product::with(
            'menu:id,name',
            'category:id,categoryName',
            'variations:product_id,id,name,price',
            'modifiers:id,product_id,modifier_group_id,is_required,is_multiple',
            'modifiers.modifier_group:id,name',
            'modifiers.modifier_group.modifier_group_option:id,modifier_group_id,is_available,name,price'
        )
            ->where('business_id', $business_id)->latest()->get();

        $staffs = Staff::where('business_id', $business_id)->where('designation', 'waiter')->latest()->get();
        $tables = Table::where('business_id', $business_id)->latest()->get();
        $categories = Category::where('business_id', $business_id)->latest()->get();
        $payment_types = PaymentType::where('business_id', $business_id)->whereStatus(1)->latest()->get();
        $vat_on_sale = Tax::where('business_id', $business_id)->where('vat_on_sale', 1)->first();

        // Add sale details to the cart
        foreach ($quotation->details as $detail) {
            $modifiers = [];
            if (!empty($detail->detail_options)) {
                foreach ($detail->detail_options as $detail_option) {
                    $modifiers[] = [
                        'option_id'   => $detail_option->option_id,
                        'modifier_id' => $detail_option->modifier_id,
                    ];
                }
            }
            // Add to cart
            Cart::add([
                'id' => $detail->product_id,
                'name' => $detail->product->productName ?? '',
                'qty' => $detail->quantities,
                'price' => $detail->price ?? 0,
                'options' => [
                    'variation_id' => $detail->variation_id,
                    'modifiers' => $modifiers,
                    'type' => 'sale',
                ]
            ]);
        }

        $cart_contents = Cart::content()->filter(fn($item) => $item->options->type == 'sale');

        return view('restaurantwebaddon::quotations.convert-sale', compact('quotation', 'customers', 'products', 'staffs', 'tables', 'categories', 'payment_types', 'vat_on_sale', 'cart_contents'));
    }

    public function generatePDF()
    {
        $quotations = Quotation::with('party:id,name', 'payment_type:id,name')
            ->where('business_id', auth()->user()->business_id)
            ->latest()
            ->get();

        $pdf = Pdf::loadView('restaurantwebaddon::quotations.pdf', compact('quotations'));
        return $pdf->download('quotation-list.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new QuotationExport, 'quotation-list.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new QuotationExport, 'quotation-list.csv');
    }
}
