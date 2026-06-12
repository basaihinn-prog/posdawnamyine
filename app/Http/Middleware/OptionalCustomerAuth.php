<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OptionalCustomerAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
           $customer = Auth::guard('customer')->user();

            $request->merge([
                'auth_customer' => $customer,
            ]);

            if ($request->has('table')) {
                $tableId = $request->table;

                if (Table::where('id', $tableId)->exists()) {
                    session(['online_store_table_id' => $tableId]);
                }
            }

            return $next($request);
    }
}
