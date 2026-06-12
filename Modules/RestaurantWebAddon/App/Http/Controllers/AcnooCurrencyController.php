<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Models\Currency;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\UserCurrency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\RestaurantWebAddon\App\Exports\ExportCurrency;

class AcnooCurrencyController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:currency.view')->only('index');
        $this->middleware('check.permission:currency.update')->only('default');
    }

    public function index()
    {
        $currencies = Currency::whereStatus(1)->orderBy('is_default', 'desc')->paginate(20);
        $user_currency = UserCurrency::where('business_id', auth()->user()->business_id)->first();
        return view('restaurantwebaddon::currencies.index', compact('currencies', 'user_currency'));
    }

    public function acnooFilter(Request $request)
    {
        $currencies = Currency::whereStatus(1)->orderBy('is_default', 'desc')
            ->when(request('search'), function ($q) {
                $q->where(function ($q) {
                    $q->where('name', 'like', '%' . request('search') . '%')
                        ->orWhere('country_name', 'like', '%' . request('search') . '%')
                        ->orWhere('code', 'like', '%' . request('search') . '%')
                        ->orWhere('symbol', 'like', '%' . request('search') . '%');
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        $user_currency = UserCurrency::where('business_id', auth()->user()->business_id)->first();

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::currencies.datas', compact('currencies', 'user_currency'))->render()
            ]);
        }

        return redirect(url()->previous());
    }

    public function default(string $id)
    {
        $currency = Currency::findOrFail($id);

        DB::beginTransaction();
        try {
            $user_currency = UserCurrency::where('business_id', auth()->user()->business_id)->first();

            if ($user_currency) {
                $user_currency->update([
                    'name' => $currency->name,
                    'currency_id' => $currency->id,
                    'country_name' => $currency->country_name,
                    'code' => $currency->code,
                    'rate' => $currency->rate,
                    'symbol' => $currency->symbol,
                    'position' => $currency->position,
                ]);
            } else {
                UserCurrency::create([
                    'currency_id' => $currency->id,
                    'business_id' => auth()->user()->business_id,
                    'name' => $currency->name,
                    'country_name' => $currency->country_name,
                    'code' => $currency->code,
                    'rate' => $currency->rate,
                    'symbol' => $currency->symbol,
                    'position' => $currency->position,
                ]);
            }

            // Clear update
            cache()->forget("business_currency_" . auth()->user()->business_id);

            DB::commit();

            return redirect()->route('business.currencies.index')->with('message', __('Default currency activated successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('business.currencies.index')->with('error', __('Failed to set default currency. Please try again.'));
        }
    }

    public function generatePDF()
    {
        $currencies = Currency::whereStatus(1)->orderBy('is_default', 'desc')->latest()->get();

        $pdf = Pdf::loadView('restaurantwebaddon::currencies.pdf', compact('currencies'));

        return $pdf->download('currencies-list.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new ExportCurrency, 'currency-list.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new ExportCurrency, 'currency-list.csv');
    }
}
