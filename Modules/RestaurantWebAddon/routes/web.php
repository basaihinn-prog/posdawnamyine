<?php

use Illuminate\Support\Facades\Route;
use Modules\RestaurantWebAddon\App\Http\Controllers as Business;

Route::group(['as' => 'business.', 'prefix' => 'business', 'middleware' => ['users', 'expired']], function () {

    Route::get('dashboard', [Business\DashboardController::class, 'index'])->name('dashboard.index');

    Route::get('/get-dashboard', [Business\DashboardController::class, 'getDashboardData'])->name('dashboard.data');
    Route::get('/money-flow', [Business\DashboardController::class, 'showMoneyFlow'])->name('dashboard.money.flow');
    Route::get('/loss-profit', [Business\DashboardController::class, 'lossProfit'])->name('dashboard.loss.profit');

    Route::resource('profiles', Business\ProfileController::class)->only('index', 'update');
    // Pos Sale
    Route::resource('sales', Business\AcnooSaleController::class)->except('show');
    Route::post('sales/filter', [Business\AcnooSaleController::class, 'acnooFilter'])->name('sales.filter');
    Route::post('sales/delete-all', [Business\AcnooSaleController::class, 'deleteAll'])->name('sales.delete-all');
    Route::get('/get-invoice/{id}', [Business\AcnooSaleController::class, 'getInvoice'])->name('sales.invoice');
    Route::get('/get-kot-ticket/{id}', [Business\AcnooSaleController::class, 'getKotTicket'])->name('sales.kotTicket');
    Route::post('sale/product-filter', [Business\AcnooSaleController::class, 'productFilter'])->name('sales.product-filter');
    Route::get('get-delivery-address', [Business\AcnooSaleController::class, 'getDeliveryAddress'])->name('sales.getDeliveryAddress');
    Route::get('get-sale-coupon', [Business\AcnooSaleController::class, 'getSaleCoupon'])->name('sales.getSaleCoupon');
    Route::post('/status/{id}', [Business\AcnooSaleController::class, 'updateStatus'])->name('sales.status');
    Route::get('sales/pdf', [Business\AcnooSaleController::class, 'generatePDF'])->name('sales.pdf');
    Route::get('sales/excel', [Business\AcnooSaleController::class, 'exportExcel'])->name('sales.excel');
    Route::get('sales/csv', [Business\AcnooSaleController::class, 'exportCsv'])->name('sales.csv');
    Route::post('create-customer', [Business\AcnooSaleController::class, 'createCustomer'])->name('sales.store.customer');
    Route::post('/order-status/{id}', [Business\AcnooSaleController::class, 'orderStatus'])->name('sales.orderStatus');

    //sales cart
    Route::resource('sale-carts', Business\SaleCartController::class);
    Route::post('sale-carts/remove-all', [Business\SaleCartController::class, 'removeAllCart'])->name('sale-carts.remove-all');

    // Quotations
    Route::resource('quotations', Business\QuotationController::class)->except('show');
    Route::post('quotations/filter', [Business\QuotationController::class, 'acnooFilter'])->name('quotations.filter');
    Route::post('quotations/delete-all', [Business\QuotationController::class, 'deleteAll'])->name('quotations.delete-all');
    Route::get('quotations/get-invoice/{id}', [Business\QuotationController::class, 'getInvoice'])->name('quotations.invoice');
    Route::get('quotations/convert-sale/{id}', [Business\QuotationController::class, 'convertSale'])->name('quotations.convert-sale');
    Route::get('quotations/pdf', [Business\QuotationController::class, 'generatePDF'])->name('quotations.pdf');
    Route::get('quotations/excel', [Business\QuotationController::class, 'exportExcel'])->name('quotations.excel');
    Route::get('quotations/csv', [Business\QuotationController::class, 'exportCsv'])->name('quotations.csv');

    Route::resource('delivery-address', Business\AcnooDeliveryAddressController::class)->only('store');

    // Purchase
    Route::resource('purchases', Business\AcnooPurchaseController::class)->except('show');
    Route::post('purchases/filter', [Business\AcnooPurchaseController::class, 'acnooFilter'])->name('purchases.filter');
    Route::post('purchases/delete-all', [Business\AcnooPurchaseController::class, 'deleteAll'])->name('purchases.delete-all');
    Route::get('/purchase-cart', [Business\AcnooPurchaseController::class, 'showPurchaseCart'])->name('purchases.cart');
    Route::get('/purchase-cart-data', [Business\AcnooPurchaseController::class, 'getCartData'])->name('purchases.cart-data');
    Route::get('/purchase/get-invoice/{id}', [Business\AcnooPurchaseController::class, 'getInvoice'])->name('purchases.invoice');
    Route::post('create-supplier', [Business\AcnooPurchaseController::class, 'createSupplier'])->name('purchases.store.supplier');
    Route::post('create-ingredient', [Business\AcnooPurchaseController::class, 'createIngredient'])->name('purchases.store.ingredient');
    Route::get('purchases/pdf', [Business\AcnooPurchaseController::class, 'generatePDF'])->name('purchases.pdf');
    Route::get('purchases/excel', [Business\AcnooPurchaseController::class, 'exportExcel'])->name('purchases.excel');
    Route::get('purchases/csv', [Business\AcnooPurchaseController::class, 'exportCsv'])->name('purchases.csv');

    Route::resource('carts', Business\CartController::class);
    Route::post('cart/remove-all', [Business\CartController::class, 'removeAllCart'])->name('carts.remove-all');

    Route::resource('reservations', Business\AcnooReservationController::class)->except('show');
    Route::get('/reservations/get-time-slots', [Business\AcnooReservationController::class, 'getTimeSlots'])->name('reservations.getTimeSlots');
    Route::post('/reservations/filter', [Business\AcnooReservationController::class, 'acnooFilter'])->name('reservations.filter');
    Route::get('/reservations/get-table', [Business\AcnooReservationController::class, 'getTables'])->name('reservations.getTables');
    Route::post('/reservations/assign-table', [Business\AcnooReservationController::class, 'assignTable'])->name('reservations.assignTable');
    Route::post('reservations/delete-all', [Business\AcnooReservationController::class, 'deleteAll'])->name('reservations.delete-all');
    Route::post('reservations/status/{id}', [Business\AcnooReservationController::class, 'updateStatus'])->name('reservations.status');

    Route::resource('due-reports', Business\AcnooDueReportController::class)->only('index');
    Route::post('due-reports/filter', [Business\AcnooDueReportController::class, 'acnooFilter'])->name('due-reports.filter');
    Route::get('due-reports/pdf', [Business\AcnooDueReportController::class, 'generatePDF'])->name('due-reports.pdf');
    Route::get('due-reports/excel', [Business\AcnooDueReportController::class, 'exportExcel'])->name('due-reports.excel');
    Route::get('due-reports/csv', [Business\AcnooDueReportController::class, 'exportCsv'])->name('due-reports.csv');

    Route::resource('supplier-due-reports', Business\AcnooSupplierDueReportController::class)->only('index');
    Route::post('supplier-due-reports/filter', [Business\AcnooSupplierDueReportController::class, 'acnooFilter'])->name('supplier-due-reports.filter');
    Route::get('supplier-due-reports/pdf', [Business\AcnooSupplierDueReportController::class, 'generatePDF'])->name('supplier-due-reports.pdf');
    Route::get('supplier-due-reports/excel', [Business\AcnooSupplierDueReportController::class, 'exportExcel'])->name('supplier-due-reports.excel');
    Route::get('supplier-due-reports/csv', [Business\AcnooSupplierDueReportController::class, 'exportCsv'])->name('supplier-due-reports.csv');

    Route::resource('sale-reports', Business\AcnooSaleReportController::class)->only('index');
    Route::post('sale-reports/filter', [Business\AcnooSaleReportController::class, 'acnooFilter'])->name('sale-reports.filter');
    Route::get('sale-reports/pdf', [Business\AcnooSaleReportController::class, 'generatePDF'])->name('sale-reports.pdf');
    Route::get('sale-reports/excel', [Business\AcnooSaleReportController::class, 'exportExcel'])->name('sale-reports.excel');
    Route::get('sale-reports/csv', [Business\AcnooSaleReportController::class, 'exportCsv'])->name('sale-reports.csv');

    Route::resource('quotation-reports', Business\AcnooQuotationReportController::class)->only('index');
    Route::post('quotation-reports/filter', [Business\AcnooQuotationReportController::class, 'acnooFilter'])->name('quotation-reports.filter');
    Route::get('quotation-reports/pdf', [Business\AcnooQuotationReportController::class, 'generatePDF'])->name('quotation-reports.pdf');
    Route::get('quotation-reports/excel', [Business\AcnooQuotationReportController::class, 'exportExcel'])->name('quotation-reports.excel');
    Route::get('quotation-reports/csv', [Business\AcnooQuotationReportController::class, 'exportCsv'])->name('quotation-reports.csv');

    Route::resource('purchase-reports', Business\AcnooPurchaseReportController::class)->only('index');
    Route::post('purchase-reports/filter', [Business\AcnooPurchaseReportController::class, 'acnooFilter'])->name('purchase-reports.filter');
    Route::get('purchase-reports/pdf', [Business\AcnooPurchaseReportController::class, 'generatePDF'])->name('purchase-reports.pdf');
    Route::get('purchase-reports/excel', [Business\AcnooPurchaseReportController::class, 'exportExcel'])->name('purchase-reports.excel');
    Route::get('purchase-reports/csv', [Business\AcnooPurchaseReportController::class, 'exportCsv'])->name('purchase-reports.csv');

    Route::resource('products', Business\AcnooProductController::class)->except('show');
    Route::post('products/filter', [Business\AcnooProductController::class, 'acnooFilter'])->name('products.filter');
    Route::delete('products/variation-delete/{id}', [Business\AcnooProductController::class, 'variationDelete'])->name('products.variationDelete');
    Route::put('products/variation-update/{id}', [Business\AcnooProductController::class, 'variationUpdate'])->name('products.variationUpdate');
    Route::post('products/delete-all', [Business\AcnooProductController::class, 'deleteAll'])->name('products.delete-all');
    Route::get('products/pdf', [Business\AcnooProductController::class, 'generatePDF'])->name('products.pdf');
    Route::get('products-excel', [Business\AcnooProductController::class, 'exportExcel'])->name('products.excel');
    Route::get('products-csv', [Business\AcnooProductController::class, 'exportCsv'])->name('products.csv');
    Route::post('products/status/{id}', [Business\AcnooProductController::class, 'status'])->name('products.status');

    // Menu
    Route::resource('menus', Business\AcnooMenuController::class)->except('show', 'create', 'edit');
    Route::post('menus/filter', [Business\AcnooMenuController::class, 'acnooFilter'])->name('menus.filter');
    Route::post('menus/delete-all', [Business\AcnooMenuController::class, 'deleteAll'])->name('menus.delete-all');
    Route::get('menus/pdf', [Business\AcnooMenuController::class, 'generatePDF'])->name('menus.pdf');
    Route::get('menus-excel', [Business\AcnooMenuController::class, 'exportExcel'])->name('menus.excel');
    Route::get('menus-csv', [Business\AcnooMenuController::class, 'exportCsv'])->name('menus.csv');

    // Payment Types
    Route::resource('payment-types', Business\AcnooPaymentTypeController::class)->except('show', 'edit', 'create');
    Route::post('payment-types/filter', [Business\AcnooPaymentTypeController::class, 'acnooFilter'])->name('payment-types.filter');
    Route::post('payment-types/status/{id}', [Business\AcnooPaymentTypeController::class, 'status'])->name('payment-types.status');
    Route::post('payment-types/delete-all', [Business\AcnooPaymentTypeController::class, 'deleteAll'])->name('payment-types.delete-all');
    Route::get('payment-types/pdf', [Business\AcnooPaymentTypeController::class, 'generatePDF'])->name('payment-types.pdf');
    Route::get('payment-types-excel', [Business\AcnooPaymentTypeController::class, 'exportExcel'])->name('payment-types.excel');
    Route::get('payment-types-csv', [Business\AcnooPaymentTypeController::class, 'exportCsv'])->name('payment-types.csv');

    // Units
    Route::resource('units', Business\AcnooUnitController::class)->except('show', 'edit', 'create');
    Route::post('units/filter', [Business\AcnooUnitController::class, 'acnooFilter'])->name('units.filter');
    Route::post('units/status/{id}', [Business\AcnooUnitController::class, 'status'])->name('units.status');
    Route::post('units/delete-all', [Business\AcnooUnitController::class, 'deleteAll'])->name('units.delete-all');
    Route::get('units/pdf', [Business\AcnooUnitController::class, 'generatePDF'])->name('units.pdf');
    Route::get('units-excel', [Business\AcnooUnitController::class, 'exportExcel'])->name('units.excel');
    Route::get('units-csv', [Business\AcnooUnitController::class, 'exportCsv'])->name('units.csv');

    // Category
    Route::resource('categories', Business\AcnooCategoryController::class)->except('show', 'create', 'edit');
    Route::post('categories/status/{id}', [Business\AcnooCategoryController::class, 'status'])->name('categories.status');
    Route::post('categories/delete-all', [Business\AcnooCategoryController::class, 'deleteAll'])->name('categories.delete-all');
    Route::post('categories/filter', [Business\AcnooCategoryController::class, 'acnooFilter'])->name('categories.filter');
    Route::get('categories/pdf', [Business\AcnooCategoryController::class, 'generatePDF'])->name('categories.pdf');
    Route::get('categories-excel', [Business\AcnooCategoryController::class, 'exportExcel'])->name('categories.excel');
    Route::get('categories-csv', [Business\AcnooCategoryController::class, 'exportCsv'])->name('categories.csv');

    // Inventory Items
    Route::resource('items', Business\AcnooInventoryItemController::class)->except('show', 'edit', 'create');
    Route::post('items/delete-all', [Business\AcnooInventoryItemController::class, 'deleteAll'])->name('items.delete-all');
    Route::post('items/filter', [Business\AcnooInventoryItemController::class, 'acnooFilter'])->name('items.filter');
    Route::get('items/pdf', [Business\AcnooInventoryItemController::class, 'generatePDF'])->name('items.pdf');
    Route::get('items-excel', [Business\AcnooInventoryItemController::class, 'exportExcel'])->name('items.excel');
    Route::get('items-csv', [Business\AcnooInventoryItemController::class, 'exportCsv'])->name('items.csv');

    // Modifier Groups
    Route::resource('modifier-groups', Business\AcnooModifierGroupController::class)->except('show');
    Route::post('modifier-groups/delete-all', [Business\AcnooModifierGroupController::class, 'deleteAll'])->name('modifier-groups.delete-all');
    Route::post('modifier-groups/filter', [Business\AcnooModifierGroupController::class, 'acnooFilter'])->name('modifier-groups.filter');
    Route::get('modifier-groups/pdf', [Business\AcnooModifierGroupController::class, 'generatePDF'])->name('modifier-groups.pdf');
    Route::get('modifier-groups-excel', [Business\AcnooModifierGroupController::class, 'exportExcel'])->name('modifier-groups.excel');
    Route::get('modifier-groups-csv', [Business\AcnooModifierGroupController::class, 'exportCsv'])->name('modifier-groups.csv');

    // Modifier
    Route::resource('modifiers', Business\AcnooModifierController::class)->except('show', 'create', 'edit');
    Route::post('modifiers/delete-all', [Business\AcnooModifierController::class, 'deleteAll'])->name('modifiers.delete-all');
    Route::post('modifiers/filter', [Business\AcnooModifierController::class, 'acnooFilter'])->name('modifiers.filter');
    Route::get('modifiers/pdf', [Business\AcnooModifierController::class, 'generatePDF'])->name('modifiers.pdf');
    Route::get('modifiers-excel', [Business\AcnooModifierController::class, 'exportExcel'])->name('modifiers.excel');
    Route::get('modifiers-csv', [Business\AcnooModifierController::class, 'exportCsv'])->name('modifiers.csv');

    // Table
    Route::resource('tables', Business\AcnooTableController::class);
    Route::post('tables/delete-all', [Business\AcnooTableController::class, 'deleteAll'])->name('tables.delete-all');
    Route::post('tables/filter', [Business\AcnooTableController::class, 'acnooFilter'])->name('tables.filter');
    Route::post('tables/status/{id}', [Business\AcnooTableController::class, 'status'])->name('tables.status');
    Route::get('tables/pdf', [Business\AcnooTableController::class, 'generatePDF'])->name('tables.pdf');
    Route::get('tables/excel', [Business\AcnooTableController::class, 'exportExcel'])->name('tables.excel');
    Route::get('tables/csv', [Business\AcnooTableController::class, 'exportCsv'])->name('tables.csv');

    Route::get('/scan/{business}/{table}', [Business\AcnooTableController::class, 'scan'])->name('table.scan');

    // Table
    Route::resource('booked-tables', Business\AcnooBookedTableController::class)->only('index');
    Route::post('booked-tables/filter', [Business\AcnooBookedTableController::class, 'acnooFilter'])->name('booked-tables.filter');
    Route::get('booked-tables/pdf', [Business\AcnooBookedTableController::class, 'generatePDF'])->name('booked-tables.pdf');
    Route::get('booked-tables/excel', [Business\AcnooBookedTableController::class, 'exportExcel'])->name('booked-tables.excel');
    Route::get('booked-tables/csv', [Business\AcnooBookedTableController::class, 'exportCsv'])->name('booked-tables.csv');

    // Area
    Route::resource('areas', Business\AcnooAreaController::class)->except('show');
    Route::post('areas/delete-all', [Business\AcnooAreaController::class, 'deleteAll'])->name('areas.delete-all');
    Route::post('areas/filter', [Business\AcnooAreaController::class, 'acnooFilter'])->name('areas.filter');
    Route::post('areas/status/{id}', [Business\AcnooAreaController::class, 'status'])->name('areas.status');
    Route::get('areas/pdf', [Business\AcnooAreaController::class, 'generatePDF'])->name('areas.pdf');
    Route::get('areas/excel', [Business\AcnooAreaController::class, 'exportExcel'])->name('areas.excel');
    Route::get('areas/csv', [Business\AcnooAreaController::class, 'exportCsv'])->name('areas.csv');

    // Coupon
    Route::resource('coupons', Business\AcnooCouponController::class)->except('show', 'edit', 'create');
    Route::post('coupons/delete-all', [Business\AcnooCouponController::class, 'deleteAll'])->name('coupons.delete-all');
    Route::post('coupons/filter', [Business\AcnooCouponController::class, 'acnooFilter'])->name('coupons.filter');
    Route::get('coupons/pdf', [Business\AcnooCouponController::class, 'generatePDF'])->name('coupons.pdf');
    Route::get('coupons/excel', [Business\AcnooCouponController::class, 'exportExcel'])->name('coupons.excel');
    Route::get('coupons/csv', [Business\AcnooCouponController::class, 'exportCsv'])->name('coupons.csv');

    // Coupon
    Route::resource('cancel-reasons', Business\AcnooCancelReasonController::class)->except('show', 'edit', 'create');
    Route::post('cancel-reasons/delete-all', [Business\AcnooCancelReasonController::class, 'deleteAll'])->name('cancel-reasons.delete-all');
    Route::post('cancel-reasons/status/{id}', [Business\AcnooCancelReasonController::class, 'status'])->name('cancel-reasons.status');
    Route::post('cancel-reasons/filter', [Business\AcnooCancelReasonController::class, 'acnooFilter'])->name('cancel-reasons.filter');

    // Staff
    Route::resource('staffs', Business\AcnooStaffController::class)->except('show', 'edit', 'create');
    Route::post('staffs/delete-all', [Business\AcnooStaffController::class, 'deleteAll'])->name('staffs.delete-all');
    Route::post('staffs/filter', [Business\AcnooStaffController::class, 'acnooFilter'])->name('staffs.filter');
    Route::get('staffs/pdf', [Business\AcnooStaffController::class, 'generatePDF'])->name('staffs.pdf');
    Route::get('staffs/excel', [Business\AcnooStaffController::class, 'exportExcel'])->name('staffs.excel');
    Route::get('staffs/csv', [Business\AcnooStaffController::class, 'exportCsv'])->name('staffs.csv');

    //Parties
    Route::resource('parties', Business\AcnooPartyController::class)->except('show');
    Route::post('parties/filter', [Business\AcnooPartyController::class, 'acnooFilter'])->name('parties.filter');
    Route::post('parties/delete-all', [Business\AcnooPartyController::class, 'deleteAll'])->name('parties.delete-all');
    Route::get('parties/pdf', [Business\AcnooPartyController::class, 'generatePDF'])->name('parties.pdf');
    Route::get('parties/excel', [Business\AcnooPartyController::class, 'exportExcel'])->name('parties.excel');
    Route::get('parties/csv', [Business\AcnooPartyController::class, 'exportCsv'])->name('parties.csv');

    //Income Category
    Route::resource('income-categories', Business\AcnooIncomeCategoryController::class)->except('show');
    Route::post('income-categories/filter', [Business\AcnooIncomeCategoryController::class, 'acnooFilter'])->name('income-categories.filter');
    Route::post('income-categories/status/{id}', [Business\AcnooIncomeCategoryController::class, 'status'])->name('income-categories.status');
    Route::post('income-categories/delete-all', [Business\AcnooIncomeCategoryController::class, 'deleteAll'])->name('income-categories.delete-all');
    Route::get('income-categories/pdf', [Business\AcnooIncomeCategoryController::class, 'generatePDF'])->name('income-categories.pdf');
    Route::get('income-categories-excel', [Business\AcnooIncomeCategoryController::class, 'exportExcel'])->name('income-categories.excel');
    Route::get('income-categories-csv', [Business\AcnooIncomeCategoryController::class, 'exportCsv'])->name('income-categories.csv');

    //Income
    Route::resource('incomes', Business\AcnooIncomeController::class)->except('show');
    Route::post('incomes/filter', [Business\AcnooIncomeController::class, 'acnooFilter'])->name('incomes.filter');
    Route::post('incomes/status/{id}', [Business\AcnooIncomeController::class, 'status'])->name('incomes.status');
    Route::post('incomes/delete-all', [Business\AcnooIncomeController::class, 'deleteAll'])->name('incomes.delete-all');
    Route::get('incomes/pdf', [Business\AcnooIncomeController::class, 'generatePDF'])->name('incomes.pdf');
    Route::get('incomes-excel', [Business\AcnooIncomeController::class, 'exportExcel'])->name('incomes.excel');
    Route::get('incomes-csv', [Business\AcnooIncomeController::class, 'exportCsv'])->name('incomes.csv');

    //Expense Category
    Route::resource('expense-categories', Business\AcnooExpenseCategoryController::class)->except('show');
    Route::post('expense-categories/filter', [Business\AcnooExpenseCategoryController::class, 'acnooFilter'])->name('expense-categories.filter');
    Route::post('expense-categories/status/{id}', [Business\AcnooExpenseCategoryController::class, 'status'])->name('expense-categories.status');
    Route::post('expense-categories/delete-all', [Business\AcnooExpenseCategoryController::class, 'deleteAll'])->name('expense-categories.delete-all');
    Route::get('expense-categories/pdf', [Business\AcnooExpenseCategoryController::class, 'generatePDF'])->name('expense-categories.pdf');
    Route::get('expense-categories-excel', [Business\AcnooExpenseCategoryController::class, 'exportExcel'])->name('expense-categories.excel');
    Route::get('expense-categories-csv', [Business\AcnooExpenseCategoryController::class, 'exportCsv'])->name('expense-categories.csv');

    //Expense
    Route::resource('expenses', Business\AcnooExpenseController::class)->except('show');
    Route::post('expenses/filter', [Business\AcnooExpenseController::class, 'acnooFilter'])->name('expenses.filter');
    Route::post('expenses/status/{id}', [Business\AcnooExpenseController::class, 'status'])->name('expenses.status');
    Route::post('expenses/delete-all', [Business\AcnooExpenseController::class, 'deleteAll'])->name('expenses.delete-all');
    Route::get('expenses/pdf', [Business\AcnooExpenseController::class, 'generatePDF'])->name('expenses.pdf');
    Route::get('expenses-excel', [Business\AcnooExpenseController::class, 'exportExcel'])->name('expenses.excel');
    Route::get('expenses-csv', [Business\AcnooExpenseController::class, 'exportCsv'])->name('expenses.csv');

    //Reports
    Route::resource('income-reports', Business\AcnooIncomeReportController::class)->only('index');
    Route::post('income-reports/filter', [Business\AcnooIncomeReportController::class, 'acnooFilter'])->name('income-reports.filter');
    Route::get('income-reports/pdf', [Business\AcnooIncomeReportController::class, 'generatePDF'])->name('income-reports.pdf');
    Route::get('income-reports/excel', [Business\AcnooIncomeReportController::class, 'exportExcel'])->name('income-reports.excel');
    Route::get('income-reports/csv', [Business\AcnooIncomeReportController::class, 'exportCsv'])->name('income-reports.csv');

    Route::resource('expense-reports', Business\AcnooExpenseReportController::class)->only('index');
    Route::post('expense-reports/filter', [Business\AcnooExpenseReportController::class, 'acnooFilter'])->name('expense-reports.filter');
    Route::get('expense-reports/pdf', [Business\AcnooExpenseReportController::class, 'generatePDF'])->name('expense-reports.pdf');
    Route::get('expense-reports/excel', [Business\AcnooExpenseReportController::class, 'exportExcel'])->name('expense-reports.excel');
    Route::get('expense-reports/csv', [Business\AcnooExpenseReportController::class, 'exportCsv'])->name('expense-reports.csv');

    Route::resource('transactions', Business\AcnooTransactionController::class)->only('index');
    Route::post('transactions/filter', [Business\AcnooTransactionController::class, 'acnooFilter'])->name('transactions.filter');
    Route::get('transactions/pdf', [Business\AcnooTransactionController::class, 'generatePDF'])->name('transactions.pdf');
    Route::get('transactions/excel', [Business\AcnooTransactionController::class, 'exportExcel'])->name('transactions.excel');
    Route::get('transactions/csv', [Business\AcnooTransactionController::class, 'exportCsv'])->name('transactions.csv');

    Route::resource('subscription-reports', Business\AcnooSubscriptionReportController::class)->only('index');
    Route::post('subscription-reports/filter', [Business\AcnooSubscriptionReportController::class, 'acnooFilter'])->name('subscription-reports.filter');
    Route::get('subscription-reports/pdf', [Business\AcnooSubscriptionReportController::class, 'generatePDF'])->name('subscription-reports.pdf');
    Route::get('subscription-reports/excel', [Business\AcnooSubscriptionReportController::class, 'exportExcel'])->name('subscription-reports.excel');
    Route::get('subscription-reports/csv', [Business\AcnooSubscriptionReportController::class, 'exportCsv'])->name('subscription-reports.csv');
    Route::get('subscription-reports/get-invoice/{id}', [Business\AcnooSubscriptionReportController::class, 'getInvoice'])->name('subscription-reports.invoice');

    // Vat Reports
    Route::resource('vat-reports', Business\AcnooVatReportController::class)->only('index');
    Route::get('vat-reports/pdf/{type?}', [Business\AcnooVatReportController::class, 'generatePDF'])->name('vat-reports.pdf');
    Route::get('vat-reports/excel/{type?}', [Business\AcnooVatReportController::class, 'exportExcel'])->name('vat-reports.excel');
    Route::get('vat-reports/csv/{type?}', [Business\AcnooVatReportController::class, 'exportCsv'])->name('vat-reports.csv');

    Route::resource('dues', Business\AcnooDueController::class)->only('index');
    Route::post('dues/filter', [Business\AcnooDueController::class, 'acnooFilter'])->name('dues.filter');
    Route::get('collect-dues/{id}', [Business\AcnooDueController::class, 'collectDue'])->name('collect.dues');
    Route::post('collect-dues/store', [Business\AcnooDueController::class, 'collectDueStore'])->name('collect.dues.store');
    Route::get('/collect-dues-invoice/{id}', [Business\AcnooDueController::class, 'getInvoice'])->name('collect.dues.invoice');

    Route::get('walk-dues', [Business\AcnooDueController::class, 'walk_dues'])->name('walk-dues.index');
    Route::post('walk-dues/filter', [Business\AcnooDueController::class, 'walk_dues_filter'])->name('walk-dues.filter');
    Route::get('/walk-dues-invoice/{id}', [Business\AcnooDueController::class, 'walkDuesGetInvoice'])->name('collect.walk-dues.invoice');

    Route::get('dues/pdf', [Business\AcnooDueController::class, 'generatePDF'])->name('dues.pdf');
    Route::get('dues/excel', [Business\AcnooDueController::class, 'exportExcel'])->name('dues.excel');
    Route::get('dues/csv', [Business\AcnooDueController::class, 'exportCsv'])->name('dues.csv');

    Route::resource('due-collect-reports', Business\AcnooDueCollectReportController::class)->only('index');
    Route::post('due-collect-reports/filter', [Business\AcnooDueCollectReportController::class, 'acnooFilter'])->name('due-collect-reports.filter');
    Route::get('due-collect-reports/pdf', [Business\AcnooDueCollectReportController::class, 'generatePDF'])->name('due-collect-reports.pdf');
    Route::get('due-collect-reports/excel', [Business\AcnooDueCollectReportController::class, 'exportExcel'])->name('due-collect-reports.excel');
    Route::get('due-collect-reports/csv', [Business\AcnooDueCollectReportController::class, 'exportCsv'])->name('due-collect-reports.csv');

    Route::resource('roles', Business\UserRoleController::class)->except('show');
    Route::resource('settings', Business\SettingController::class)->only('index', 'update');
    Route::resource('subscriptions', Business\AcnooSubscriptionController::class)->withoutMiddleware('expired')->only('index');

    Route::resource('manage-settings', Business\AcnooSettingsManagerController::class);
    Route::resource('reservation-settting', Business\AcnooReservationSettingController::class)->only('index', 'store');
    Route::get('reservation-settting/data', [Business\AcnooReservationSettingController::class, 'getData'])->name('reservation-setting.getData');
    Route::resource('order-type-setting', Business\AcnooOrderTypeSettingController::class)->only('index', 'update');
    Route::resource('payment-gateway', Business\AcnooPaymentGatewayController::class)->only('index', 'store');
    Route::resource('delivery-charge', Business\AcnooDeliveryChargeController::class)->only('index', 'update');
    Route::resource('discount', Business\AcnooDiscountController::class)->only('index', 'update');

    Route::resource('currencies', Business\AcnooCurrencyController::class)->only('index');
    Route::post('currencies/filter', [Business\AcnooCurrencyController::class, 'acnooFilter'])->name('currencies.filter');
    Route::match(['get', 'post'], 'currencies/default/{id}', [Business\AcnooCurrencyController::class, 'default'])->name('currencies.default');
    Route::get('currencies/pdf', [Business\AcnooCurrencyController::class, 'generatePDF'])->name('currencies.pdf');
    Route::get('currencies-excel', [Business\AcnooCurrencyController::class, 'exportExcel'])->name('currencies.excel');
    Route::get('currencies-csv', [Business\AcnooCurrencyController::class, 'exportCsv'])->name('currencies.csv');

    Route::resource('newsletters', BUSINESS\AcnooNewsLetterController::class)->only('index');
    Route::post('newsletters/filter', [Business\AcnooNewsLetterController::class, 'acnooFilter'])->name('newsletters.filter');

    //Message
    Route::resource('messages', Business\AcnooMessageController::class)->only('index', 'destroy');
    Route::post('messages/delete-all', [Business\AcnooMessageController::class, 'deleteAll'])->name('messages.delete-all');

    Route::resource('vats', Business\AcnooVatController::class)->except('show');
    Route::post('vats/status/{id}', [Business\AcnooVatController::class, 'status'])->name('vats.status');
    Route::post('vats/vat-apply/{id}', [Business\AcnooVatController::class, 'vatApply'])->name('vats.vat-apply');
    Route::post('vats/delete-all', [Business\AcnooVatController::class, 'deleteAll'])->name('vats.deleteAll');
    Route::post('vat/filter', [Business\AcnooVatController::class, 'acnooFilter'])->name('vats.filter');
    Route::post('vat-group/filter', [Business\AcnooVatController::class, 'VatGroupFilter'])->name('vat-groups.filter');
    Route::get('vats/pdf', [Business\AcnooVatController::class, 'generatePDF'])->name('vats.pdf');
    Route::get('vats/excel', [Business\AcnooVatController::class, 'exportExcel'])->name('vats.excel');
    Route::get('vats/csv', [Business\AcnooVatController::class, 'exportCsv'])->name('vats.csv');

    // Kot list
    Route::resource('kots', Business\AcnooKotController::class)->only('index');
    Route::post('kots/cooking-status/{id}', [Business\AcnooKotController::class, 'cookingStatus'])->name('kots-cooking-status');
    Route::post('kots/kot-status/{id}', [Business\AcnooKotController::class, 'kotStatus'])->name('kots-kot-status');
    Route::get('kots/get-ticket/{id}', [Business\AcnooKotController::class, 'getKotTicket'])->name('kots.get-ticket');

    // Kot reports
    Route::resource('kot-reports', Business\AcnooKotReportController::class)->only('index');
    Route::get('kot-reports/pdf', [Business\AcnooKotReportController::class, 'exportPdf'])->name('kot-reports.pdf');
    Route::get('kot-reports/excel', [Business\AcnooKotReportController::class, 'exportExcel'])->name('kot-reports.excel');
    Route::get('kot-reports/csv', [Business\AcnooKotReportController::class, 'exportCsv'])->name('kot-reports.csv');

    Route::prefix('notifications')->controller(Business\AcnooNotificationController::class)->name('notifications.')->group(function () {
        Route::get('/', 'mtIndex')->name('index');
        Route::post('/filter', 'maanFilter')->name('filter');
        Route::get('/{id}', 'mtView')->name('mtView');
        Route::get('view/all/', 'mtReadAll')->name('mtReadAll');
    });
});
