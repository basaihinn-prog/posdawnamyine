<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Models\Sale;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\SaleDetails;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:dashboard.view')->only('index');
    }

    public function index()
    {
        $businessId = auth()->user()->business_id;

        // Latest sales
        $sales = Sale::with('party:id,name', 'details')
            ->where('business_id', $businessId)
            ->latest()
            ->limit(5)
            ->get();

        // Latest purchases
        $purchases = Purchase::with('details', 'party:id,name')
            ->where('business_id', $businessId)
            ->latest()
            ->limit(5)
            ->get();

        // Top sales todays
        $top_sales = SaleDetails::with('product:id,productName,sales_price,category_id,price_type,images', 'product.category:id,categoryName', 'product.variations:product_id,price')
            ->select('product_id', DB::raw('SUM(quantities) as qty'))
            ->whereHas('sale', function ($q) use ($businessId) {
                $q->where('business_id', $businessId)
                    ->whereDate('saleDate', Carbon::today());
            })
            ->groupBy('product_id')
            ->orderByDesc('qty')
            ->limit(5)
            ->get();

        $latestSaleIds = Sale::where('business_id', $businessId)
            ->whereDate('saleDate', Carbon::today())
            ->groupBy('party_id')
            ->select(DB::raw('MAX(id) as id'))
            ->pluck('id')
            ->take(5);

        // Todays order
        $todays_order = Sale::with(['party:id,name', 'payment_type:id,name'])
            ->whereIn('id', $latestSaleIds)
            ->orderByDesc('id')
            ->get();

        return view('restaurantwebaddon::dashboard.index', compact('purchases', 'sales', 'top_sales', 'todays_order'));
    }

    public function getDashboardData()
    {
        $businessId = auth()->user()->business_id;

        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        // Orders
        $totalOrders = Sale::where('business_id', $businessId)->count();
        $todayOrders = Sale::where('business_id', $businessId)->whereDate('saleDate', $today)->count();
        $yesterdayOrders = Sale::where('business_id', $businessId)->whereDate('saleDate', $yesterday)->count();
        $orderDiff = $todayOrders - $yesterdayOrders;

        // Items
        $totalItems = Product::where('business_id', $businessId)->count();
        $todayItems = Product::where('business_id', $businessId)->whereDate('created_at', $today)->count();
        $yesterdayItems = Product::where('business_id', $businessId)->whereDate('created_at', $yesterday)->count();
        $itemDiff = $todayItems - $yesterdayItems;

        // Sales
        $totalSales = Sale::where('business_id', $businessId)->sum('totalAmount');
        $todaySales = Sale::where('business_id', $businessId)->whereDate('saleDate', $today)->sum('totalAmount');
        $yesterdaySales = Sale::where('business_id', $businessId)->whereDate('saleDate', $yesterday)->sum('totalAmount');
        $salesDiff = $todaySales - $yesterdaySales;
        $salesPercent = $yesterdaySales > 0 ? round(($salesDiff / $yesterdaySales) * 100, 2) : 0;

        // Expense
        $totalExpense = Expense::where('business_id', $businessId)->sum('amount');
        $todayExpense = Expense::where('business_id', $businessId)->whereDate('expenseDate', $today)->sum('amount');
        $yesterdayExpense = Expense::where('business_id', $businessId)->whereDate('expenseDate', $yesterday)->sum('amount');
        $expenseDiff = $todayExpense - $yesterdayExpense;
        $expensePercent = $yesterdayExpense > 0 ? round(($expenseDiff / $yesterdayExpense) * 100, 2) : 0;

        return response()->json([
            // Order
            'total_order'   => $totalOrders,
            'order_status'  => $orderDiff >= 0 ? 'up' : 'down',
            'order_diff'    => $orderDiff,

            // Item
            'total_items'   => $totalItems,
            'item_status'   => $itemDiff >= 0 ? 'up' : 'down',
            'item_diff'     => $itemDiff,

            // Sales
            'total_sales'   => $totalSales,
            'sales_status' => $salesDiff >= 0 ? 'up' : 'down',
            'sales_percent' => abs($salesPercent),

            // Expense
            'total_expense'   => $totalExpense,
            'expense_status' => $expenseDiff >= 0 ? 'up' : 'down',
            'expense_percent' => abs($expensePercent),
        ]);
    }

    public function showMoneyFlow(Request $request)
    {
        $businessId = auth()->user()->business_id;
        $year = $request->get('year', date('Y'));

        $money_in = DB::table('sales')
            ->select(DB::raw("MONTH(saleDate) as month"), DB::raw("SUM(totalAmount) as amount"))
            ->where('business_id', $businessId)
            ->whereYear('saleDate', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('amount', 'month');

        $money_out = DB::table('purchases')
            ->select(DB::raw("MONTH(purchaseDate) as month"), DB::raw("SUM(totalAmount) as amount"))
            ->where('business_id', $businessId)
            ->whereYear('purchaseDate', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('amount', 'month');

        $data = [
            'money_in' => $money_in,
            'money_out' => $money_out
        ];

        return response()->json($data);
    }

    public function lossProfit(Request $request)
    {
        $businessId = auth()->user()->business_id;
        $year = $request->get('year', date('Y'));

        $loss_total = DB::table('expenses')
            ->where('business_id', $businessId)
            ->whereYear('expenseDate', $year)
            ->sum('amount');

        $profit_total = DB::table('incomes')
            ->where('business_id', $businessId)
            ->whereYear('incomeDate', $year)
            ->sum('amount');

        return response()->json([
            'loss_total' => $loss_total,
            'profit_total' => $profit_total,
        ]);
    }
}
