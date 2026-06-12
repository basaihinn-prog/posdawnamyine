<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Models\Menu;
use App\Models\Product;
use App\Models\Category;
use App\Models\Modifier;
use App\Helpers\HasUploader;
use Illuminate\Http\Request;
use App\Models\ModifierGroups;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RestaurantWebAddon\App\Exports\ProductExport;

class AcnooProductController extends Controller
{
    use HasUploader;

    public function __construct()
    {
        $this->middleware('check.permission:products.view')->only('index');
        $this->middleware('check.permission:products.create')->only('create', 'store');
        $this->middleware('check.permission:products.update')->only('edit', 'update', 'variationUpdate');
        $this->middleware('check.permission:products.delete')->only('destroy', 'deleteAll', 'variationDelete');
    }

    public function index()
    {
        $products = Product::with(
            'variations',
            'menu:id,name',
            'category:id,categoryName',
            'modifiers:id,product_id,modifier_group_id,is_required,is_multiple',
            'modifiers.modifier_group:id,name',
            'modifiers.modifier_group.modifier_group_option:id,modifier_group_id,is_available,name,price'
        )
            ->withCount('variations')
            ->where('business_id', auth()->user()->business_id)
            ->latest()
            ->paginate(20);

        return view('restaurantwebaddon::products.index', compact('products'));
    }

    public function acnooFilter(Request $request)
    {
        $search = $request->input('search');
        $products = Product::with(
            'variations',
            'menu:id,name',
            'category:id,categoryName',
            'modifiers:id,product_id,modifier_group_id,is_required,is_multiple',
            'modifiers.modifier_group:id,name',
            'modifiers.modifier_group.modifier_group_option:id,modifier_group_id,is_available,name,price'
        )
            ->withCount('variations')
            ->where('business_id', auth()->user()->business_id)
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('productName', 'like', '%' . $search . '%')
                        ->orWhere('sales_price', 'like', '%' . $search . '%')
                        ->orWhereHas('category', function ($q) use ($search) {
                            $q->where('categoryName', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('menu', function ($q) use ($search) {
                            $q->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 10);


        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::products.datas', compact('products'))->render()
            ]);
        }

        return redirect(url()->previous());
    }

    public function create()
    {
        $business_id = auth()->user()->business_id;
        $categories = Category::where('business_id', $business_id)->whereStatus(1)->latest()->get();
        $menus = Menu::where('business_id', $business_id)->latest()->get();
        $modifier_groups = ModifierGroups::where('business_id', auth()->user()->business_id)->latest()->get();

        return view('restaurantwebaddon::products.create', compact('categories', 'menus', 'modifier_groups'));
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
            $product = Product::create($request->except('business_id', 'sales_price', 'images') + [
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

            if (!empty($request->modifier_group_id)) {
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
                'message' => __('Item saved successfully.'),
                'redirect' => route('business.products.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => __('Something went wrong')
            ]);
        }
    }

    public function edit(string $id)
    {
        $business_id = auth()->user()->business_id;
        $product = Product::findOrFail($id);
        $categories = Category::where('business_id', $business_id)->whereStatus(1)->latest()->get();
        $menus = Menu::where('business_id', $business_id)->latest()->get();
        $modifier_groups = ModifierGroups::where('business_id', auth()->user()->business_id)->latest()->get();

        return view('restaurantwebaddon::products.edit', compact('categories', 'menus', 'product', 'modifier_groups'));
    }

    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
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

            $product->update($request->except('business_id', 'sales_price', 'images') + [
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
                'message' => __('Item updated successfully.'),
                'redirect' => route('business.products.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => __('Something was wrong.'),
            ], 406);
        }
    }

    public function variationUpdate(Request $request, string $id)
    {
        $variation =  ProductVariation::findOrFail($id);
        $variation->update($request->all());

        return response()->json([
            'message' => __('Variation updated successfully'),
            'redirect' => route('business.products.index')
        ]);
    }

    public function variationDelete(string $id)
    {
        ProductVariation::where('id', $id)->delete();

        return response()->json([
            'message' => __('Variation deleted successfully'),
            'redirect' => route('business.products.index')
        ]);
    }

    public function status(Request $request, string $id)
    {
        $status = Product::findOrFail($id);
        $status->update(['status' => $request->status]);
        return response()->json(['message' => __('Product')]);
    }


    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        if (!empty($product->images)) {
            foreach ($product->images as $image) {
                if (file_exists($image)) {
                    Storage::delete($image);
                }
            }
        }

        $product->delete();

        return response()->json([
            'message' => __('Item deleted successfully'),
            'redirect' => route('business.products.index')
        ]);
    }

    public function deleteAll(Request $request)
    {
        $products = Product::whereIn('id', $request->ids)->get();

        foreach ($products as $product) {
            if (!empty($product->images)) {
                foreach ($product->images as $image) {
                    if (file_exists($image)) {
                        Storage::delete($image);
                    }
                }
            }

            $product->delete();
        }

        return response()->json([
            'message'   => __('Selected item deleted successfully'),
            'redirect'  => route('business.products.index')
        ]);
    }

    public function generatePDF(Request $request)
    {
        $products = Product::with('menu:id,name', 'category:id,categoryName')
            ->withCount('variations')
            ->where('business_id', auth()->user()->business_id)
            ->latest()
            ->get();

        $pdf = Pdf::loadView('restaurantwebaddon::products.pdf', compact('products'));

        return $pdf->download('item-list.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new ProductExport, 'item-list.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new ProductExport, 'item-list.csv');
    }
}
