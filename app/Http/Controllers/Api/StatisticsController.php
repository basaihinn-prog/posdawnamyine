<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\KotTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class StatisticsController extends Controller
{
    public function summary(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $business_id = auth()->user()->business_id;

        $DateFilter = function ($query) use ($from_date, $to_date) {
            if ($from_date && $to_date) {
                $query->whereDate('created_at', '>=', $from_date)
                      ->whereDate('created_at', '<=', $to_date);
            }
            return $query;
        };

        $CustomDateFilter = function ($query, $column) use ($from_date, $to_date) {
            if ($from_date && $to_date) {
                $query->whereDate($column, '>=', $from_date)
                      ->whereDate($column, '<=', $to_date);
            }
            return $query;
        };

        $data['total_sales'] = $CustomDateFilter(Sale::where('business_id', $business_id), 'saleDate')->sum('totalAmount');
        $data['total_purchase'] = $CustomDateFilter(Purchase::where('business_id', $business_id), 'purchaseDate')->sum('totalAmount');
        $data['total_items'] = $DateFilter(Product::where('business_id', $business_id))->count();
        $data['total_hold'] = $DateFilter(KotTicket::where('business_id', $business_id))->count();
        $data['total_expense'] = $DateFilter(Expense::where('business_id', $business_id))->sum('amount');

        return response()->json([
            'message' => 'Data fetched successfully',
            'data' => $data,
        ]);
    }

    public function dashboard()
    {
        $currentDate = Carbon::now();
        switch (request('duration')) {
            case 'weekly':
                $start = $currentDate->copy()->startOfWeek(Carbon::SATURDAY);
                $end = $currentDate->copy()->endOfWeek(Carbon::FRIDAY);
                $format = 'D';
                $period = $start->daysUntil($end);
                break;

            case 'monthly':
                $start = $currentDate->copy()->startOfMonth();
                $end = $currentDate->copy()->endOfMonth();
                $format = 'd';
                $period = $start->daysUntil($end);
                break;

            case 'yearly':
                $start = $currentDate->copy()->startOfYear();
                $end = $currentDate->copy()->endOfYear();
                $format = 'M';
                $period = $start->monthsUntil($end);
                break;

            default:
                return response()->json(['error' => 'Invalid duration'], 400);
        }

        $business_id = auth()->user()->business_id;

        $money_in = DB::table('sales')
                        ->select(DB::raw("DATE_FORMAT(saleDate, '%Y-%m-%d') as date"), DB::raw("SUM(totalAmount) as amount"))
                        ->where('business_id', $business_id)
                        ->whereBetween('saleDate', [$start, $end])
                        ->groupBy('date')
                        ->orderBy('date')
                        ->get()
                        ->keyBy('date');

        $money_out = DB::table('purchases')
                            ->select(DB::raw("DATE_FORMAT(purchaseDate, '%Y-%m-%d') as date"), DB::raw("SUM(totalAmount) as amount"))
                            ->where('business_id', $business_id)
                            ->whereBetween('purchaseDate', [$start, $end])
                            ->groupBy('date')
                            ->orderBy('date')
                            ->get()
                            ->keyBy('date');

        $loss_data = DB::table('expenses')
                        ->select(DB::raw("DATE_FORMAT(expenseDate, '%Y-%m-%d') as date"), DB::raw("SUM(amount) as amount"))
                        ->where('business_id', $business_id)
                        ->whereBetween('expenseDate', [$start, $end])
                        ->groupBy('date')
                        ->orderBy('date')
                        ->get()
                        ->keyBy('date');

        $profit_data = DB::table('incomes')
                        ->select(DB::raw("DATE_FORMAT(incomeDate, '%Y-%m-%d') as date"), DB::raw("SUM(amount) as amount"))
                        ->where('business_id', $business_id)
                        ->whereBetween('incomeDate', [$start, $end])
                        ->groupBy('date')
                        ->orderBy('date')
                        ->get()
                        ->keyBy('date');

        $sale_amount = Sale::where('business_id', $business_id)->whereBetween('saleDate', [$start, $end])->sum('totalAmount');
        $purchase_amount = Purchase::where('business_id', $business_id)->whereBetween('purchaseDate', [$start, $end])->sum('totalAmount');

        $total_loss = (float) array_sum($loss_data->pluck('amount')->toArray());
        $total_profit = (float) array_sum($profit_data->pluck('amount')->toArray());

        $total = $total_loss + $total_profit;

        // Avoid division by zero
        $loss_percentage = $total > 0 ? ($total_loss / $total) * 100 : 0;
        $profit_percentage = $total > 0 ? ($total_profit / $total) * 100 : 0;

        // Find max and min values
        $max_value = max($sale_amount, $purchase_amount);
        $min_value = min($sale_amount, $purchase_amount);

        $data = [
            'total_loss' => $total_loss,
            'total_profit' => $total_profit,
            'loss_percentage' => round($loss_percentage, 2),
            'profit_percentage' => round($profit_percentage, 2),
            'total_money_in' => (float) array_sum($money_in->pluck('amount')->toArray()),
            'total_money_out' => (float) array_sum($money_out->pluck('amount')->toArray()),
            'max_value' => (float) $max_value,
            'min_value' => (float) $min_value,
            'money_in' => $this->formatData($period, $money_in, $format),
            'money_out' =>$this->formatData($period, $money_out, $format),
        ];

        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => $data,
        ]);
    }

    private function formatData(iterable $period, Collection $datas, string $format)
    {
        $rows = [];
        foreach ($period as $date) {
            if (request('duration') == 'yearly') {
                $key = $date->format($format);
                $dateKey = $date->format('Y-m'); // For lookup purposes
                $amount = $datas->filter(function ($value, $key) use ($dateKey) {
                    return strpos($value->date, $dateKey) === 0;
                })->sum('amount');
            } else {
                $key = $date->format($format);
                $amount = $datas->get($date->format('Y-m-d'))?->amount ?? 0;
            }

            $rows[] = [
                'date' => $key,
                'amount' => $amount,
            ];
        }

        return $rows;
    }
}
