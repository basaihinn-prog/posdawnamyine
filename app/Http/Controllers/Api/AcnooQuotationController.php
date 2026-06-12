<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\QuotationDetails;

class AcnooQuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quotations = Quotation::select('id', 'business_id', 'party_id', 'invoiceNumber', 'totalAmount', 'quotationDate')
                        ->with('party:id,name,type')
                        ->where('business_id', auth()->user()->business_id)
                        ->when(request('search'), function($query) {
                            $query->where('invoiceNumber', 'like', '%'. request('search'). '%')
                                    ->orWhereHas('party', function($q) {
                                        $q->where('name', 'like', '%'.request('search').'%');
                                    });
                        })
                        ->latest()
                        ->paginate(10);

            return response()->json([
                'message' => __('Data fetched successfully.'),
                'data' => $quotations
            ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'quotationDate' => 'required|string',
            'party_id' => 'nullable|exists:parties,id',
            'tax_id' => 'nullable|exists:taxes,id',
            'coupon_id' => 'nullable|exists:coupons,id',
            'discountAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'discountPercentage' => 'nullable|numeric',
            'discount_type' => 'nullable|string',
            'tax_amount' => 'nullable|numeric|min:0|max:99999999.99',
            'totalAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.sales_price' => 'required|numeric|min:0|max:99999999.99',
            'products.*.quantities' => 'required|integer',
            'products.*.variation_id' => 'nullable|integer|exists:product_variations,id',
            'products.*.detail_options.*.option_id' => 'nullable|integer|exists:modifier_group_options,id',
            'products.*.detail_options.*.modifier_id' => 'nullable|integer|exists:modifiers,id',
        ]);

        DB::beginTransaction();
        try {

            $business_id = auth()->user()->business_id;

            $quotation = Quotation::create($request->all() + [
                        'user_id' => auth()->id(),
                        'business_id' => $business_id,
                    ]);

                $quotationDetails = [];

                foreach ($request->products as $key => $productData) {
                    $quotationDetail = QuotationDetails::create([
                        'quotation_id' => $quotation->id,
                        'product_id' => $productData['product_id'],
                        'variation_id' => $productData['variation_id'] ?? NULL,
                        'price' => $productData['sales_price'],
                        'quantities' => $productData['quantities'] ?? 0,
                        'instructions' => $productData['instructions'],
                    ]);

                    // Insert quotation_detail_options if exists
                    if (!empty($productData['detail_options'])) {
                        $detailOptionData = [];
                        foreach ($productData['detail_options'] as $option) {
                            $detailOptionData[] = [
                                'quotation_detail_id' => $quotationDetail->id,
                                'option_id' => $option['option_id'],
                                'modifier_id' => $option['modifier_id'],
                            ];
                        }
                        DB::table('quotation_detail_options')->insert($detailOptionData);
                    }

                    $quotationDetails[] = $quotationDetail;
                }

            DB::commit();

            return response()->json([
                'message' => 'Quotation created successfully',
                'data' => $quotation->load([
                    'coupon',
                    'tax:id,name,rate',
                    'party:id,name,phone',
                    'payment_type:id,name',
                    'details:id,quotation_id,product_id,variation_id,price,quantities,instructions',
                    'details.product:id,productName,sales_price,price_type',
                    'details.variation:id,name,price',
                    'details.detail_options:id,quotation_detail_id,option_id,modifier_id',
                    'details.detail_options.modifier_group_option:id,name,price',
                ])
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => __('Something was wrong.'),
            ], 406);
        }
    }

    public function show(string $id)
    {
        $quotation = Quotation::with([
                    'coupon',
                    'tax',
                    'party',
                    'payment_type:id,name',
                    'user:id,name',
                    'delivery_address:id,name,phone,address',
                    'payment_type:id,name',
                    'details:id,quotation_id,product_id,variation_id,price,quantities,instructions',
                    'details.product:id,productName,sales_price,price_type,images',
                    'details.variation:id,name,price',
                    'details.detail_options:id,quotation_detail_id,option_id,modifier_id',
                    'details.detail_options.modifier_group_option:id,name,price',
                ])
                ->findOrFail($id);

                return response()->json([
                    'message' => __('Data fetched successfully.'),
                    'data' => $quotation
                ]);
    }

    public function update(Request $request, Quotation $quotation)
    {
        $request->validate([
            'products' => 'required|array',
            'quotationDate' => 'required|string',
            'party_id' => 'nullable|exists:parties,id',
            'tax_id' => 'nullable|exists:taxes,id',
            'coupon_id' => 'nullable|exists:coupons,id',
            'discountAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'discountPercentage' => 'nullable|numeric',
            'discount_type' => 'nullable|string',
            'tax_amount' => 'nullable|numeric|min:0|max:99999999.99',
            'totalAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.sales_price' => 'required|numeric|min:0|max:99999999.99',
            'products.*.quantities' => 'required|integer',
            'products.*.variation_id' => 'nullable|integer|exists:product_variations,id',
            'products.*.detail_options.*.option_id' => 'nullable|integer|exists:modifier_group_options,id',
            'products.*.detail_options.*.modifier_id' => 'nullable|integer|exists:modifiers,id',
        ]);

        $business_id = auth()->user()->business_id;

        DB::beginTransaction();
        try {

            $prevDetails = QuotationDetails::where('quotation_id', $quotation->id)->get();

            $prevDetails->each->delete();

            // Save new quotation details
            $quotationDetails = [];

            foreach ($request->products as $key => $productData) {
                $quotationDetail = QuotationDetails::create([
                    'quotation_id' => $quotation->id,
                    'product_id' => $productData['product_id'],
                    'variation_id' => $productData['variation_id'] ?? NULL,
                    'price' => $productData['sales_price'],
                    'quantities' => $productData['quantities'] ?? 0,
                    'instructions' => $productData['instructions'],
                ]);

                // Insert quotation_detail_options if exists
                if (!empty($productData['detail_options'])) {
                    $detailOptionData = [];
                    foreach ($productData['detail_options'] as $option) {
                        $detailOptionData[] = [
                            'quotation_detail_id' => $quotationDetail->id,
                            'option_id' => $option['option_id'],
                            'modifier_id' => $option['modifier_id'],
                        ];
                    }
                    DB::table('quotation_detail_options')->insert($detailOptionData);
                }

                $quotationDetails[] = $quotationDetail;
            }

            $quotation->update($request->all() + [
                'user_id' => auth()->id(),
                'business_id' => $business_id,
            ]);

            DB::commit();

            return response()->json([
                'message' => __('Quotation updated successfully.'),
                'data' => $quotation->load([
                    'coupon',
                    'tax:id,name,rate',
                    'party:id,name,phone',
                    'payment_type:id,name',
                    'details:id,quotation_id,product_id,variation_id,price,quantities,instructions',
                    'details.product:id,productName,sales_price,price_type,images',
                    'details.variation:id,name,price',
                    'details.detail_options:id,quotation_detail_id,option_id,modifier_id',
                    'details.detail_options.modifier_group_option:id,name,price',
                ])
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => __('Something was wrong.'),
            ], 406);
        }
    }

    public function destroy(Quotation $quotation)
    {
        $quotation->delete();

        return response()->json([
            'message' => __('Data deleted successfully.')
        ]);
    }
}
