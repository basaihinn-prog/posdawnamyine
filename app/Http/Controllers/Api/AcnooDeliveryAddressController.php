<?php

namespace App\Http\Controllers\Api;

use App\Models\Party;
use Illuminate\Http\Request;
use App\Models\DeliveryAddress;
use App\Http\Controllers\Controller;

class AcnooDeliveryAddressController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'party_id' => 'required|integer|exists:parties,id',
            'name' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        $party = Party::where('id', $request->party_id)->first();

        if($party->type != 'customer') {
            return response()->json([
                'message' => 'Delivery Address can be added only for customer',
            ]);
        }

        $delivery_address = DeliveryAddress::create([
            'party_id' => $request->party_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address, 
        ]);

        return response()->json([
            'message' => 'Delivery Address save successfully',
            'data' => $delivery_address
        ]);
    }

}
