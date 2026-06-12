<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Models\Party;
use App\Helpers\HasUploader;
use Illuminate\Http\Request;
use App\Models\DeliveryAddress;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RestaurantWebAddon\App\Exports\PartyExport;

class AcnooPartyController extends Controller
{
    use HasUploader;

    public function __construct()
    {
        $this->middleware('check.permission:parties.view')->only('index');
        $this->middleware('check.permission:parties.create')->only('create', 'store');
        $this->middleware('check.permission:parties.update')->only('edit', 'update');
        $this->middleware('check.permission:parties.delete')->only('destroy', 'deleteAll');
    }

    public function index()
    {
        $business_id = auth()->user()->business_id;
        $party_type = request('type');

        $query = Party::with('delivery_addresses')->where('business_id', $business_id);

        if ($party_type === 'customer') {
            $query->where('type', 'customer');
        } elseif ($party_type === 'supplier') {
            $query->where('type', 'supplier');
        }

        $parties = $query->latest()->paginate(20);

        return view('restaurantwebaddon::parties.index', compact('parties', 'party_type'));
    }

    public function acnooFilter(Request $request)
    {
        $search = $request->input('search');
        $party_type = $request->input('type');

        $parties = Party::where('business_id', auth()->user()->business_id)
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%')
                        ->orWhere('type', 'like', '%' . $search . '%')
                        ->orWhere('address', 'like', '%' . $search . '%')
                        ->orWhere('due', 'like', '%' . $search . '%');
                });
            })
            ->when($request->payment_status, function ($q) use ($request) {
                if ($request->payment_status === 'paid') {
                    $q->where('due', '=', 0);
                } elseif ($request->payment_status === 'due') {
                    $q->where('due', '>', 0);
                }
            });

        if ($party_type === 'customer') {
            $parties->where('type', 'customer');
        } elseif ($party_type === 'supplier') {
            $parties->where('type', 'supplier');
        }

        $parties = $parties->latest()->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::parties.datas', compact('parties'))->render()
            ]);
        }

        return redirect(url()->previous());
    }

    public function create()
    {
        return view('restaurantwebaddon::parties.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|max:20|unique:parties,phone',
            'opening_balance' => 'nullable|numeric|min:0|max:99999999.99',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'type' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {

            $party = Party::create($request->except('image', 'opening_balance') + [
                'opening_balance' => $request->opening_balance ?? 0,
                'due' => $request->opening_balance ?? 0,
                'business_id' => auth()->user()->business_id,
                'user_id' => auth()->id(),
                'image' => $request->image ? $this->upload($request, 'image') : NULL,
            ]);

            if ($party->type === 'customer' && is_array($request->delivery_name)) {
                foreach ($request->delivery_name as $index => $name) {
                    $address = $request->delivery_address[$index] ?? null;

                    if (!empty($name) || !empty($request->delivery_phone[$index]) || !empty($address)) {
                        DeliveryAddress::create([
                            'party_id' => $party->id,
                            'name' => $name,
                            'phone' => $request->delivery_phone[$index] ?? null,
                            'address' => $address,
                        ]);
                    }
                }
            }

            DB::commit();

            $type = ($request->type === 'customer') ? 'customer' : ($request->type === 'supplier' ? 'supplier' : '');

            return response()->json([
                'message'   => __(':type created successfully', ['type' => __($type)]),
                'redirect'  => route('business.parties.index', ['type' => $type])
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => __('Something went wrong.'),
            ], 500);
        }
    }

    public function edit(string $id)
    {
        $party = Party::with('delivery_addresses')->where('business_id', auth()->user()->business_id)->findOrFail($id);

        return view('restaurantwebaddon::parties.edit', compact('party'));
    }

    public function update(Request $request, string $id)
    {
        $party = Party::findOrFail($id);

        $request->validate([
            'phone' => 'nullable|max:20|unique:parties,phone,' . $party->id . ',id,business_id,' . auth()->user()->business_id,
            'name' => 'required|string|max:255',
            'type' => 'nullable|string',
            'email' => 'nullable|email',
            'image' => 'nullable|image|max:1024',
            'address' => 'nullable|string|max:255',
            'opening_balance' => 'nullable|numeric|min:0|max:99999999.99',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {

            $openingBalance = $request->opening_balance ?? 0;

            if ($party->type === 'supplier') {
                $pendingDues = $party->purchases_dues()->sum('dueAmount');
                // Prevent reducing due below pending dues
                if ($openingBalance < $pendingDues) {
                    return response()->json([
                        'message' => __('You cannot reduce the due amount below the currently pending Purchase dues (' . $pendingDues . '). Please settle the dues first.')
                    ], 406);
                }

                $party->opening_balance = $openingBalance;
                $party->due = $pendingDues + $openingBalance;
            } else {
                // For customers or other types
                $party->opening_balance = $openingBalance;
                $party->due = $openingBalance;
            }

            $party->update($request->except('image', 'opening_balance') + [
                'opening_balance' => $party->opening_balance,
                'due' => $party->due,
                'business_id' => auth()->user()->business_id,
                'user_id' => auth()->id(),
                'image' => $request->image ? $this->upload($request, 'image', $party->image) : $party->image,
            ]);

            // Update or insert delivery addresses if type is customer
            if ($party->type === 'customer' && is_array($request->delivery_name)) {
                $existingIds = [];
                foreach ($request->delivery_name as $index => $name) {
                    $address_id = $request->address_id[$index] ?? null;
                    $phone = $request->delivery_phone[$index] ?? null;
                    $address = $request->delivery_address[$index] ?? null;

                    // Skip if all fields are empty
                    if (empty($name) && empty($phone) && empty($address)) {
                        continue;
                    }

                    $delivery =  DeliveryAddress::updateOrCreate(
                        ['id' => $address_id, 'party_id' => $party->id],
                        [
                            'name' => $name,
                            'phone' => $phone,
                            'address' => $address,
                        ]
                    );

                    $existingIds[] = $delivery->id;
                }

                DeliveryAddress::where('party_id', $party->id)
                    ->whereNotIn('id', $existingIds)
                    ->delete();
            }

            DB::commit();

            $type = ($request->type === 'customer') ? 'customer' : ($request->type === 'supplier' ? 'supplier' : '');

            return response()->json([
                'message'   => __(':type updated successfully', ['type' => __($type)]),
                'redirect'  => route('business.parties.index', ['type' => $type])
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => __('Something went wrong.'),
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        $party = Party::findOrFail($id);
        if (file_exists($party->image)) {
            Storage::delete($party->image);
        }

        $party->delete();

        return response()->json([
            'message' => __(':type deleted successfully', ['type' => __($party->type)]),
            'redirect' => route('business.parties.index', ['type' => $party->type]),
        ]);
    }

    public function deleteAll(Request $request)
    {
        $parties = Party::whereIn('id', $request->ids)->get();

        $type = $parties->first()->type;

        foreach ($parties as $party) {
            if (file_exists($party->image)) {
                Storage::delete($party->image);
            }
        }

        Party::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message'   => __('Selected parties deleted successfully'),
            'redirect'  => route('business.parties.index', ['type' => $type])
        ]);
    }

    public function generatePDF(Request $request)
    {
        $query = Party::where('business_id', auth()->user()->business_id);

        if ($request->type === 'customer') {
            $query->where('type', 'customer');
        } elseif ($request->type === 'supplier') {
            $query->where('type', 'supplier');
        }

        $parties = $query->latest()->get();

        $pdf = Pdf::loadView('restaurantwebaddon::parties.pdf', compact('parties'));
        return $pdf->download('party-list.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new PartyExport, 'party-list.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new PartyExport, 'party-list.csv');
    }
}
