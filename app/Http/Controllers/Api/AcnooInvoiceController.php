<?php

namespace App\Http\Controllers\Api;

use App\Models\Sale;
use App\Models\Party;
use App\Models\Purchase;
use App\Models\KotTicket;
use App\Models\DueCollect;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AcnooInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'party_id' => 'required|exists:parties,id'
        ]);

        $party = Party::select('id', 'due', 'name', 'type', 'opening_balance')->find(request('party_id'));

        if ($party->type == 'Supplier')
        {
            $data = $party->load('purchases:id,party_id,dueAmount,paidAmount,totalAmount,invoiceNumber');
        } else {
            $data = $party->load('sales:id,party_id,dueAmount,paidAmount,totalAmount,invoiceNumber');
        }

        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => $data,
        ]);
    }

    public function newInvoice(Request $request)
    {
        $request->validate([
            'platform' => 'required|in:sales,purchases,due_collects,sales_return,purchases_return'
        ]);

        if ($request->platform == 'sales') {
            $prefix = '#';
            $saleCount = Sale::where('business_id', auth()->user()->business_id)->count();
            $kotCount = KotTicket::where('business_id', auth()->user()->business_id)->count();
            $id = $saleCount + $kotCount;
        } elseif ($request->platform == 'purchases') {
            $prefix = '#';
            $id = Purchase::where('business_id', auth()->user()->business_id)->count();
        } elseif ($request->platform == 'purchases_return') {
            $prefix = '#';
            $id = Purchase::where('business_id', auth()->user()->business_id)->count();
        } else {
            $prefix = '#';
            $id = DueCollect::where('business_id', auth()->user()->business_id)->count();
        }

        $invoice = $prefix . $id + 1;

        return response()->json([
            'data' => $invoice
        ]);
    }
}
