<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Helpers\HasUploader;
use App\Models\Gateway;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Models\BusinessGateway;
use App\Http\Controllers\Controller;

class AcnooPaymentGatewayController extends Controller
{
    use HasUploader;
    public function index()
    {
        $business_id = auth()->user()->business_id;
        // $gateways = Gateway::where('is_manual', 0)->latest()->get();
        $currencies = Currency::latest()->get();
        $gateways = Gateway::where('is_manual', 0)
                        ->with([
                            'businessGateway' => function ($q) use ($business_id) {
                                $q->where('business_id', $business_id);
                            }
                        ])->latest()->get();

        return view('restaurantwebaddon::payment-gateway.index', compact('gateways', 'currencies'));
    }

    public function store(Request $request)
    {
       $businessId = auth()->user()->business_id;
       $gateway = Gateway::findOrFail($request->gateway_id);

        $businessGateway = BusinessGateway::where([
            'business_id' => $businessId,
            'gateway_id' => $gateway->id,
        ])->first();

        $data = [
            'name' => $request->name,
            'charge' => $request->charge,
            'currency_id' => $request->currency_id,
            'mode' => $request->mode,
            'status' => $request->status,
            'is_manual' => $request->is_manual,
            'data' => $request->data,
            'manual_data' => $request->manual_data,
            'namespace' => $gateway->namespace,
            'instructions' => $request->instructions,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image') ? $this->upload($request, 'image', $businessGateway ? $businessGateway->image : null) : null;
        }

        BusinessGateway::updateOrCreate(
            [
                'business_id' => $businessId,
                'gateway_id' => $gateway->id,
            ],
            $data
        );

        return response()->json(__('Gateway updated successfully'));
    }
}
