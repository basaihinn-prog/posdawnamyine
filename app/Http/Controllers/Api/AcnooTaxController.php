<?php

namespace App\Http\Controllers\Api;

use App\Models\Tax;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AcnooTaxController extends Controller
{
    public function index()
    {
        $taxes = Tax::where('business_id', auth()->user()->business_id)
                    ->when(request('type') == 'single', function ($query) {
                        $query->whereNull('sub_tax');
                    })
                    ->when(request('type') == 'group', function ($query) {
                        $query->whereNotNull('sub_tax');
                    })
                    ->when(request('status'), function ($query) {
                        $query->where('status', request('status') == 'active' ? 1 : 0);
                    })
                    ->latest()
                    ->get();

        return response()->json([
            'message' => 'Data fetched successfully.',
            'data' => $taxes,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tax_ids' => 'required_if:rate,null',
            'rate' => 'required_if:rate,null|numeric|min:0|max:99999999.99',
        ]);

        $business_id = auth()->user()->business_id;
        $vatOnSale = $request->vat_on_sale ?? false;

        if ($request->rate && !$request->tax_ids) {

            $tax = Tax::create($request->all() + [
                'business_id' => $business_id,
            ]);

        } elseif (!$request->rate && $request->tax_ids) {

            $taxs = Tax::whereIn('id', $request->tax_ids)->select('id', 'name', 'rate')->get();

            $tax_rate = 0;
            $sub_taxes = [];

            foreach ($taxs as $tax) {
                $sub_taxes[] = [
                    'id' => $tax->id,
                    'name' => $tax->name,
                    'rate' => $tax->rate,
                ];
                $tax_rate += $tax->rate;
            }

            $tax = Tax::create([
                'vat_on_sale' => $request->vat_on_sale,
                'rate' => $tax_rate,
                'sub_tax' => $sub_taxes,
                'name' => $request->name,
                'status' => $request->status,
                'business_id' => $business_id,
            ]);

        } else {
            return response()->json([
                'message' => 'Invalid data format.',
            ], 406);
        }

        if ($vatOnSale) {
            Tax::where('id', '!=', $tax->id)
                ->where('business_id', $business_id)
                ->where('vat_on_sale', true)
                ->update(['vat_on_sale' => false]);
        }

        return response()->json([
            'message' => 'Data created successfully.',
            'data' => $tax,
        ]);
    }

    public function update(Request $request, Tax $tax)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tax_ids' => 'required_if:rate,null',
            'rate' => 'required_if:tax_ids,null|numeric|min:0|max:99999999.99',
        ]);

        $business_id = auth()->user()->business_id;
        $vatOnSale = $request->vat_on_sale ?? false;

        if ($request->rate && !$request->tax_ids) {

           $tax->update($request->all());

            $taxGroupExist = Tax::where('sub_tax', 'LIKE', '%"id":' . $tax->id . '%')->get();
            foreach ($taxGroupExist as $group) {
                $subTaxes = collect($group->sub_tax)->map(function ($subTax) use ($tax) {
                    if ($subTax['id'] == $tax->id) {
                        $subTax['rate'] = $tax->rate;
                        $subTax['name'] = $tax->name;
                    }
                    return $subTax;
                });

                $group->update([
                    'rate' => $subTaxes->sum('rate'),
                    'sub_tax' => $subTaxes->toArray(),
                ]);
            }

        } elseif (!$request->rate && $request->tax_ids) {

            $taxes = Tax::whereIn('id', $request->tax_ids)->select('id', 'name', 'rate')->get();

            $tax_rate = 0;
            $sub_taxes = [];

            foreach ($taxes as $single_tax) {
                $sub_taxes[] = [
                    'id' => $single_tax->id,
                    'name' => $single_tax->name,
                    'rate' => $single_tax->rate,
                ];
                $tax_rate += $single_tax->rate;
            }

            $tax->update([
                'vat_on_sale' => $request->vat_on_sale,
                'rate' => $tax_rate,
                'sub_tax' => $sub_taxes,
                'name' => $request->name,
                'status' => $request->status ?? $tax->status,
            ]);

        } else {
            return response()->json([
                'message' => 'Invalid data format.',
            ], 406);
        }

        if ($vatOnSale) {
            Tax::where('id', '!=', $tax->id)
                ->where('business_id', $business_id)
                ->where('vat_on_sale', true)
                ->update(['vat_on_sale' => false]);
        }

        return response()->json([
            'message' => 'Data updated successfully.',
            'data' => $tax,
        ]);
    }

    public function destroy(Tax $tax)
    {
        if (is_null($tax->sub_tax) && Tax::where('sub_tax', 'LIKE', '%"id":' . $tax->id . '%')->exists()) {
            return response()->json([
                'message' => 'Cannot delete. This tax is part of a tax group.',
            ], 409);
        }

        $tax->delete();
        return response()->json([
            'message' => 'Data deleted successfully',
        ]);
    }
}
