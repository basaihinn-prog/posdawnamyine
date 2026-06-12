<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class ExpiredMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!plan_data() || !plan_data()->will_expire || plan_data()->will_expire < now()) {
            $message = __('Your plan has expired. Please subscribe to a new plan.');

            $disabledRoutes = [
                'business.dashboard.index',
                'business.profiles.index',
                'business.profiles.update',
                'business.sales.index',
                'business.sales.create',
                'business.sales.edit',
                'business.sales.store',
                'business.sales.update',
                'business.sales.destroy',
                'business.sales.delete-all',
                'business.sales.store.customer',
                'business.purchases.index',
                'business.purchases.create',
                'business.purchases.edit',
                'business.purchases.store',
                'business.purchases.update',
                'business.purchases.destroy',
                'business.purchases.delete-all',
                'business.purchases.store.supplier',
                'business.tables.index',
                'business.tables.store',
                'business.tables.update',
                'business.tables.destroy',
                'business.tables.delete-all',
                'business.products.index',
                'business.products.create',
                'business.products.edit',
                'business.products.store',
                'business.products.update',
                'business.products.destroy',
                'business.products.delete-all',
                'business.menus.index',
                'business.menus.store',
                'business.menus.delete-all',
                'business.menus.update',
                'business.menus.destroy',
                'business.payment-types.index',
                'business.payment-types.store',
                'business.payment-types.update',
                'business.payment-types.destroy',
                'business.payment-types.delete-all',
                'business.units.index',
                'business.units.store',
                'business.units.update',
                'business.units.destroy',
                'business.units.delete-all',
                'business.modifier-groups.index',
                'business.modifier-groups.create',
                'business.modifier-groups.edit',
                'business.modifier-groups.store',
                'business.modifier-groups.update',
                'business.modifier-groups.destroy',
                'business.modifier-groups.delete-all',
                'business.modifiers.index',
                'business.modifiers.store',
                'business.modifiers.update',
                'business.modifiers.destroy',
                'business.modifiers.delete-all',
                'business.categories.index',
                'business.categories.store',
                'business.categories.update',
                'business.categories.destroy',
                'business.categories.delete-all',
                'business.parties.index',
                'business.parties.create',
                'business.parties.edit',
                'business.parties.store',
                'business.parties.update',
                'business.parties.destroy',
                'business.parties.delete-all',
                'business.coupons.index',
                'business.coupons.store',
                'business.coupons.update',
                'business.coupons.destroy',
                'business.coupons.delete-all',
                'business.income-categories.index',
                'business.income-categories.store',
                'business.income-categories.update',
                'business.income-categories.destroy',
                'business.income-categories.delete-all',
                'business.incomes.index',
                'business.incomes.store',
                'business.incomes.update',
                'business.incomes.destroy',
                'business.incomes.delete-all',
                'business.expense-categories.index',
                'business.expense-categories.store',
                'business.expense-categories.update',
                'business.expense-categories.destroy',
                'business.expense-categories.delete-all',
                'business.expenses.index',
                'business.expenses.store',
                'business.expenses.update',
                'business.expenses.destroy',
                'business.expenses.delete-all',
                'business.walk-dues.index',
                'business.dues.index',
                'business.collect.dues',
                'business.collect.dues.store',
                'business.roles.index',
                'business.roles.create',
                'business.roles.edit',
                'business.roles.store',
                'business.roles.update',
                'business.roles.destroy',
                'business.staffs.index',
                'business.staffs.store',
                'business.staffs.update',
                'business.staffs.destroy',
                'business.staffs.delete-all',
                'business.vats.index',
                'business.vats.create',
                'business.vats.edit',
                'business.vats.store',
                'business.vats.update',
                'business.vats.destroy',
                'business.vats.deleteAll',
                'business.sale-reports.index',
                'business.purchase-reports.index',
                'business.vat-reports.index',
                'business.income-reports.index',
                'business.expense-reports.index',
                'business.due-reports.index',
                'business.supplier-due-reports.index',
                'business.subscription-reports.index',
                'business.settings.index',
                'business.settings.update',
                'business.notifications.index',
                'business.currencies.index',
                'business.currencies.default',
                'business.manage-settings.index'
            ];

            if ($request->isMethod('delete')) {
                return response()->json($message, 406);
            }

            if (in_array(Route::currentRouteName(), $disabledRoutes)) {
                return $request->wantsJson()
                    ? response()->json($message, 406)
                    : redirect(route('business.subscriptions.index'))->with('error', $message);
            }
        }

        return $next($request);
    }
}
