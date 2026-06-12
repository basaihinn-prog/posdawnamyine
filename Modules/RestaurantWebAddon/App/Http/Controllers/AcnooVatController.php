<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RestaurantWebAddon\App\Exports\VatExport;

class AcnooVatController extends Controller
{

    public function __construct()
    {
        $this->middleware('check.permission:vat.view')->only('index');
        $this->middleware('check.permission:vat.create')->only('create', 'store');
        $this->middleware('check.permission:vat.update')->only('edit', 'update', 'status');
        $this->middleware('check.permission:vat.delete')->only('destroy', 'deleteAll');
    }

    public function index(Request $request)
    {
        $vats = Tax::where('business_id', auth()->user()->business_id)->orderBy('status', 'desc')->whereNull('sub_tax')->latest()->paginate(20);
        $vat_groups = Tax::where('business_id', auth()->user()->business_id)->orderBy('status', 'desc')->whereNotNull('sub_tax')->latest()->paginate(20);
        return view('restaurantwebaddon::vats.index', compact('vats', 'vat_groups'));
    }

    public function acnooFilter(Request $request)
    {
        $vats = Tax::where('business_id', auth()->user()->business_id)->whereNull('sub_tax')
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $search = $request->search;
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('rate', 'like', "%$search%");
                });
            })
            ->latest()
            ->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::vats.datas', compact('vats'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    public function VatGroupFilter(Request $request)
    {
        $vat_groups = Tax::where('business_id', auth()->user()->business_id)->whereNotNull('sub_tax')
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $search = $request->search;
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('rate', 'like', "%$search%")
                        ->orWhere('sub_tax', 'like', '%"name":"' . $search . '%');
                });
            })
            ->latest()
            ->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::vat-groups.datas', compact('vat_groups'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    // Vat Group Create
    public function create()
    {
        $vats = Tax::where('business_id', auth()->user()->business_id)->where('status', '1')->whereNull('sub_tax')->latest()->get();
        return view('restaurantwebaddon::vat-groups.create', compact('vats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'vat_ids' => 'required_if:rate,null',
            'rate' => 'required_if:rate,null|numeric|min:0|max:99999999.99',
        ]);

        $business_id = auth()->user()->business_id;
        $vatOnSale = $request->vat_on_sale ?? false;

        if ($request->rate && !$request->vat_ids) {

            $vat = Tax::create($request->all() + [
                'business_id' => $business_id,
            ]);
        } elseif (!$request->rate && $request->vat_ids) {

            $vats = Tax::whereIn('id', $request->vat_ids)->select('id', 'name', 'rate')->get();

            $vat_rate = 0;
            $sub_taxs = [];

            foreach ($vats as $vat) {
                $sub_taxs[] = [
                    'id' => $vat->id,
                    'name' => $vat->name,
                    'rate' => $vat->rate,
                ];
                $vat_rate += $vat->rate;
            }

            $vat = Tax::create([
                'vat_on_sale' => $request->vat_on_sale,
                'rate' => $vat_rate,
                'sub_tax' => $sub_taxs,
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
            Tax::where('id', '!=', $vat->id)
                ->where('business_id', $business_id)
                ->where('vat_on_sale', true)
                ->update(['vat_on_sale' => false]);
        }

        return response()->json([
            'message' => __('Vat created successfully.'),
            'redirect' => route('business.vats.index'),
        ]);
    }

    // Vat Group Edit
    public function edit(string $id)
    {
        $vat = Tax::where('business_id', auth()->user()->business_id)->findOrFail($id);
        $vats = Tax::where('business_id', auth()->user()->business_id)->where('status', '1')->whereNull('sub_tax')->latest()->paginate(20);
        return view('restaurantwebaddon::vat-groups.edit', compact('vat', 'vats'));
    }

    public function update(Request $request, Tax $vat)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'vat_ids' => 'required_if:rate,null',
            'rate' => 'required_if:tax_ids,null|numeric|min:0|max:99999999.99',
        ]);

        $business_id = auth()->user()->business_id;
        $vatOnSale = $request->vat_on_sale ?? false;

        if ($request->rate && !$request->vat_ids) {

            $vat->update($request->all());

            $vatGroupExist = Tax::where('sub_tax', 'LIKE', '%"id":' . $vat->id . '%')->get();
            foreach ($vatGroupExist as $group) {
                $subVats = collect($group->sub_tax)->map(function ($subVat) use ($vat) {
                    if ($subVat['id'] == $vat->id) {
                        $subVat['rate'] = $vat->rate;
                        $subVat['name'] = $vat->name;
                    }
                    return $subVat;
                });

                $group->update([
                    'rate' => $subVats->sum('rate'),
                    'sub_tax' => $subVats->toArray(),
                ]);
            }
        } elseif (!$request->rate && $request->vat_ids) {

            $vats = Tax::whereIn('id', $request->vat_ids)->select('id', 'name', 'rate')->get();

            $vat_rate = 0;
            $sub_taxs = [];

            foreach ($vats as $single_vat) {
                $sub_taxs[] = [
                    'id' => $single_vat->id,
                    'name' => $single_vat->name,
                    'rate' => $single_vat->rate,
                ];
                $vat_rate += $single_vat->rate;
            }

            $vat->update([
                'vat_on_sale' => $request->vat_on_sale,
                'rate' => $vat_rate,
                'sub_tax' => $sub_taxs,
                'name' => $request->name,
                'status' => $request->status ?? $vat->status,
            ]);
        } else {
            return response()->json([
                'message' => 'Invalid data format.',
            ], 406);
        }

        if ($vatOnSale) {
            Tax::where('id', '!=', $vat->id)
                ->where('business_id', $business_id)
                ->where('vat_on_sale', true)
                ->update(['vat_on_sale' => false]);
        }

        return response()->json([
            'message' => __('Vat updated successfully.'),
            'redirect' => route('business.vats.index'),
        ]);
    }

    public function destroy(Tax $vat)
    {
        // When sub_tax is null
        if (is_null($vat->sub_tax)) {
            // Check if this VAT exists in any other VAT's sub_tax
            $vatGroupExist = Tax::where('sub_tax', 'LIKE', '%"id":' . $vat->id . '%')->exists();

            if ($vatGroupExist) {
                return response()->json([
                    'message' => "Cann't delete. This VAT is part of a VAT group.",
                ], 404);
            }
        }

        $vat->delete();

        return response()->json([
            'message' => __('VAT deleted successfully'),
            'redirect' => route('business.vats.index'),
        ]);
    }

    public function status(Request $request, string $id)
    {
        $status = Tax::findOrFail($id);
        $status->update(['status' => $request->status]);
        return response()->json(['message' => 'Vat']);
    }

    public function vatApply(Request $request, string $id)
    {
        $vat_on_sale = Tax::where('business_id', auth()->user()->business_id)->findOrFail($id);

        if ($request->vat_on_sale) {
            Tax::where('business_id', auth()->user()->business_id)
                ->where('id', '!=', $vat_on_sale->id)
                ->update(['vat_on_sale' => false]);
        }

        $vat_on_sale->update(['vat_on_sale' => $request->vat_on_sale]);

        return response()->json(['message' => 'Vat On Sale']);
    }

    public function deleteAll(Request $request)
    {
        $vats = Tax::whereIn('id', $request->ids)->get();

        // Filter out VATs that are part of a VAT group when sub_tax is null
        $restrictedVats = $vats->filter(function ($vat) {
            return is_null($vat->sub_tax) &&
                Tax::where('sub_tax', 'LIKE', '%"id":' . $vat->id . '%')->exists();
        });

        // If there are restricted VATs
        if ($restrictedVats->isNotEmpty()) {
            return response()->json([
                'message' => "Some VAT's cann't be deleted as they are part of a VAT group.",
            ], 404);
        }

        Tax::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => __('Selected vat deleted successfully.'),
            'redirect' => route('business.vats.index'),
        ]);
    }

    public function generatePDF(Request $request)
    {
        $vats = Tax::where('business_id', auth()->user()->business_id)->latest()->get();
        $pdf = Pdf::loadView('restaurantwebaddon::vats.pdf', compact('vats'));
        return $pdf->download('vat-list.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new VatExport, 'vat-list.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new VatExport, 'vat-list.csv');
    }
}
