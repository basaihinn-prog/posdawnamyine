<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Modifier;
use App\Helpers\HasUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\Storage;

class AcnooProductController extends Controller
{
    use HasUploader;

    public function index()
    {
        $data = Product::with('menu:id,name', 'category:id,categoryName', 'variations:product_id,id,price')
                ->where('business_id', auth()->user()->business_id)
                ->when(request('search'), function ($query) {
                    $query->where('productName', 'like', '%' . request('search') . '%');
                })
                ->when(request('category_id'), function ($query) {
                    $query->where('category_id', request('category_id'));
                })
                ->when(request('menu_id'), function ($query) {
                    $query->where('menu_id', request('menu_id'));
                })
                ->when(request('food_type'), function ($query) {
                    $query->where('food_type', request('food_type'));
                })
                ->when(request('sort_by'), function ($query) {
                    if (request('sort_by') == 'low_to_high') {
                        $query->orderBy('sales_price', 'asc');
                    } elseif (request('sort_by') == 'high_to_low') {
                        $query->orderBy('sales_price', 'desc');
                    }
                })
                ->latest();
                if (request('no_paginate') && request('no_paginate') == true) {
                    $data = Product::select('id', 'business_id', 'productName')
                            ->where('business_id', auth()->user()->business_id)
                            ->get();
                    $responseData = [
                        'data' => $data,
                    ];
                } else {
                    $data = $data->paginate(10);
                    $responseData = $data;
                }

        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => $responseData,
        ]);
    }

    public function store(Request $request)
    {
        $business_id = auth()->user()->business_id;

        $request->validate([
            'productName' => 'required|string',
            'menu_id' => 'required|integer|exists:menus,id',
            'category_id' => 'required|integer|exists:categories,id',
            'food_type' => 'nullable|string',
            'price_type' => 'required|in:single,variation',
            'sales_price' => 'required_if:price_type,single|nullable|numeric|min:0|max:99999999.99',
            'preparation_time' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $product = Product::create($request->except('business_id', 'sales_price', 'images') +[
                'business_id' => $business_id,
                'user_id' => auth()->id(),
                'sales_price' => $request->price_type === 'single' ? $request->sales_price : 0,
                'images' => $request->images ? $this->multipleUpload($request, 'images') : NULL
            ]);

             // If variation, store in product_variations table
             if ($request->price_type === 'variation') {
                foreach ($request->variation_names as $index => $name) {
                    $price = $request->variation_prices[$index] ?? 0;
                    if (!empty($name)) {
                        ProductVariation::create([
                            'product_id' => $product->id,
                            'name' => $name,
                            'price' => $price,
                        ]);
                    }
                }
            }

            if(!empty($request->modifier_group_id)) {
                foreach ($request->modifier_group_id ?? [] as $modifier_group_id) {
                    //add modifier
                     Modifier::create([
                        'business_id' => $business_id,
                        'product_id' => $product->id,
                        'modifier_group_id' => $modifier_group_id,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => __('Product saved successfully.'),
                'data' => $product,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => __('Something went wrong')
            ]);
        }
    }

    public function show(string $id)
    {
        $data = Product::with('menu:id,name',
                        'category:id,categoryName',
                        'variations:product_id,id,name,price',
                        'modifiers:id,product_id,modifier_group_id,is_required,is_multiple',
                        'modifiers.modifier_group:id,name',
                        'modifiers.modifier_group.modifier_group_option:id,modifier_group_id,is_available,name,price')
                        ->findOrFail($id);

        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => $data,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $business_id = auth()->user()->business_id;

        $request->validate([
            'productName' => 'required|string',
            'menu_id' => 'required|integer|exists:menus,id',
            'category_id' => 'required|integer|exists:categories,id',
            'food_type' => 'nullable|string',
            'price_type' => 'required|in:single,variation',
            'sales_price' => 'required_if:price_type,single|nullable|numeric|min:0|max:99999999.99',
            'preparation_time' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {

            if ($request->removed_images) {

                $prev_images = array_diff($product->images ?? [], $request->removed_images);
                foreach ($request->removed_images as $image) {
                    if (Storage::exists($image)) {
                        Storage::delete($image);
                    }
                }

                $prev_images = array_values($prev_images);
            } else {
                $prev_images = $product->images ?? [];
            }

            $new_images = $request->images ? $this->multipleUpload($request, 'images') : [];
            $merged_images = array_merge($prev_images, $new_images);

            $product->update($request->except('business_id', 'sales_price', 'images') +[
                'business_id' => $business_id,
                'user_id' => auth()->id(),
                'sales_price' => $request->price_type === 'single' ? $request->sales_price : 0,
                'images' => $merged_images
            ]);

            // Handle Variations
            $product->variations()->delete(); // Remove existing
            if ($request->price_type === 'variation' && !empty($request->variation_names)) {
                foreach ($request->variation_names as $index => $name) {
                    $price = $request->variation_prices[$index] ?? 0;
                    if (!empty($name)) {
                        ProductVariation::create([
                            'product_id' => $product->id,
                            'name' => $name,
                            'price' => $price,
                        ]);
                    }
                }
            }

            $product->modifiers()->delete();
            if (!empty($request->modifier_group_id)) {
                // Create new modifiers and their details
                foreach ($request->modifier_group_id as $modifier_group_id) {
                   Modifier::create([
                        'business_id' => $business_id,
                        'product_id' => $product->id,
                        'modifier_group_id' => $modifier_group_id,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => __('Data updated successfully.'),
                'data' => $product,
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => __('Something was wrong.'),
            ], 406);
        }
    }

    public function destroy(Product $product)
    {
        foreach ($product->images ?? [] as $image) {
            if (Storage::exists($image)) {
                Storage::delete($image);
            }
        }

        $product->delete();

        return response()->json([
            'message' => __('Data deleted successfully.'),
        ]);
    }

}
