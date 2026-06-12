<?php

use App\Http\Controllers\Admin as ADMIN;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {
    Route::get('/', [ADMIN\DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/get-dashboard', [ADMIN\DashboardController::class, 'getDashboardData'])->name('dashboard.data');
    Route::get('/yearly-subscriptions', [ADMIN\DashboardController::class, 'yearlySubscriptions'])->name('dashboard.subscriptions');
    Route::get('/plans-overview', [ADMIN\DashboardController::class, 'subscriptionPlan'])->name('dashboard.plans-overview');

    Route::resource('users', ADMIN\UserController::class);
    Route::post('users/filter', [ADMIN\UserController::class, 'acnooFilter'])->name('users.filter');
    Route::post('users/status/{id}', [ADMIN\UserController::class,'status'])->name('users.status');
    Route::post('users/delete-all', [ADMIN\UserController::class,'deleteAll'])->name('users.delete-all');
    Route::get('users-excel', [ADMIN\UserController::class, 'exportExcel'])->name('users.excel');
    Route::get('users-csv', [ADMIN\UserController::class, 'exportCsv'])->name('users.csv');

    //Subscription Plans
    Route::resource('plans', ADMIN\AcnooPlanController::class)->except('show');
    Route::post('plans/filter', [ADMIN\AcnooPlanController::class, 'acnooFilter'])->name('plans.filter');
    Route::post('plans/status/{id}', [ADMIN\AcnooPlanController::class,'status'])->name('plans.status');
    Route::post('plans/delete-all', [ADMIN\AcnooPlanController::class, 'deleteAll'])->name('plans.delete-all');
    Route::get('plans-excel', [ADMIN\AcnooPlanController::class, 'exportExcel'])->name('plans.excel');
    Route::get('plans-csv', [ADMIN\AcnooPlanController::class, 'exportCsv'])->name('plans.csv');
    Route::match(['get', 'post'], 'plans/popular/{id}', [ADMIN\AcnooPlanController::class, 'popular'])->name('plans.popular');

    // Business
    Route::resource('business',ADMIN\AcnooBusinessController::class);
    Route::put('business/upgrade-plan/{id}', [ADMIN\AcnooBusinessController::class, 'upgradePlan'])->name('business.upgrade.plan');
    Route::post('business/filter', [ADMIN\AcnooBusinessController::class, 'acnooFilter'])->name('business.filter');
    Route::post('business/status/{id}',[ADMIN\AcnooBusinessController::class,'status'])->name('business.status');
    Route::post('business/delete-all', [ADMIN\AcnooBusinessController::class,'deleteAll'])->name('business.delete-all');
    Route::get('business-excel', [ADMIN\AcnooBusinessController::class, 'exportExcel'])->name('business.excel');
    Route::get('business-csv', [ADMIN\AcnooBusinessController::class, 'exportCsv'])->name('business.csv');

    Route::resource('subscription', ADMIN\AcnooSubscriptionController::class)->only('index');
    Route::post('subscription/filter', [ADMIN\AcnooSubscriptionController::class, 'acnooFilter'])->name('subscription.filter');
    Route::post('subscription/reject/{id}',[ADMIN\AcnooSubscriptionController::class,'reject'])->name('subscription.reject');
    Route::post('subscription/paid/{id}',[ADMIN\AcnooSubscriptionController::class,'paid'])->name('subscription.paid');
    Route::get('subscription/get-invoice/{id}', [ADMIN\AcnooSubscriptionController::class, 'getInvoice'])->name('subscription.invoice');
    Route::get('subscription-excel', [ADMIN\AcnooSubscriptionController::class, 'exportExcel'])->name('subscription.excel');
    Route::get('subscription-csv', [ADMIN\AcnooSubscriptionController::class, 'exportCsv'])->name('subscription.csv');

    // Business Categories
    Route::resource('business-categories',ADMIN\AcnooBusinessCategoryController::class)->except('show');
    Route::post('business-category/filter', [ADMIN\AcnooBusinessCategoryController::class, 'acnooFilter'])->name('business-categories.filter');
    Route::post('business-categories/status/{id}',[ADMIN\AcnooBusinessCategoryController::class,'status'])->name('business-categories.status');
    Route::post('business-categories/delete-all', [ADMIN\AcnooBusinessCategoryController::class,'deleteAll'])->name('business-categories.delete-all');
    Route::get('business-categories-excel', [ADMIN\AcnooBusinessCategoryController::class, 'exportExcel'])->name('business-categories.excel');
    Route::get('business-categories-csv', [ADMIN\AcnooBusinessCategoryController::class, 'exportCsv'])->name('business-categories.csv');

    Route::resource('profiles', ADMIN\ProfileController::class)->only('index', 'update');
    Route::resource('subscription-reports', ADMIN\SubscriptionReport::class)->only('index');
    Route::post('subscription-reports/filter', [ADMIN\SubscriptionReport::class, 'acnooFilter'])->name('subscription-reports.filter');
    Route::get('subscription-reports-excel', [ADMIN\SubscriptionReport::class, 'exportExcel'])->name('subscription-reports.excel');
    Route::get('subscription-reports-csv', [ADMIN\SubscriptionReport::class, 'exportCsv'])->name('subscription-reports.csv');

    Route::resource('manual-payments', ADMIN\AcnooManualPaymentReportController::class)->only('index');
    Route::post('manual-payments/filter', [ADMIN\AcnooManualPaymentReportController::class, 'acnooFilter'])->name('manual-payments.filter');
    Route::post('manual-payments/reject/{id}',[ADMIN\AcnooManualPaymentReportController::class,'reject'])->name('manual-payments.reject');
    Route::post('manual-payments/paid/{id}',[ADMIN\AcnooManualPaymentReportController::class,'paid'])->name('manual-payments.paid');
    Route::get('manual-payments/get-invoice/{id}', [ADMIN\AcnooManualPaymentReportController::class, 'getInvoice'])->name('manual-payments.invoice');
    Route::get('manual-payments-excel', [ADMIN\AcnooManualPaymentReportController::class, 'exportExcel'])->name('manual-payments.excel');
    Route::get('manual-payments-csv', [ADMIN\AcnooManualPaymentReportController::class, 'exportCsv'])->name('manual-payments.csv');

    // Expired Business
    Route::resource('expired-business',ADMIN\AcnooExpireBusinessReportController::class)->only('index');
    Route::post('expired-business/filter', [ADMIN\AcnooExpireBusinessReportController::class, 'acnooFilter'])->name('expired-business.filter');
    Route::get('expired-business-excel', [ADMIN\AcnooExpireBusinessReportController::class, 'exportExcel'])->name('expired-business.excel');
    Route::get('expired-business-csv', [ADMIN\AcnooExpireBusinessReportController::class, 'exportCsv'])->name('expired-business.csv');

    // Active Business
    Route::resource('active-stores',ADMIN\AcnooActiveBusinessReportController::class)->only('index');
    Route::post('active-stores/filter', [ADMIN\AcnooActiveBusinessReportController::class, 'acnooFilter'])->name('active-stores.filter');
    Route::get('active-stores-excel', [ADMIN\AcnooActiveBusinessReportController::class, 'exportExcel'])->name('active-stores.excel');
    Route::get('active-stores-csv', [ADMIN\AcnooActiveBusinessReportController::class, 'exportCsv'])->name('active-stores.csv');

    // Roles & Permissions
    Route::resource('roles', ADMIN\RoleController::class)->except('show');
    Route::resource('permissions', ADMIN\PermissionController::class)->only('index', 'store');

    // Settings
    Route::resource('settings', ADMIN\SettingController::class)->only('index', 'update');
    Route::resource('system-settings', ADMIN\SystemSettingController::class)->only('index', 'store');
    Route::resource('domain-settings', ADMIN\AcnooDomainSettingController::class)->only('index', 'store');

    //addon
    Route::resource('addons', ADMIN\AddonController::class)->only('index', 'store', 'show');

    // Gateway
    Route::resource('gateways', ADMIN\GatewayController::class)->only('index', 'update');

    Route::resource('currencies', ADMIN\AcnooCurrencyController::class)->except('show');
    Route::post('currencies/filter', [ADMIN\AcnooCurrencyController::class, 'acnooFilter'])->name('currencies.filter');
    Route::match(['get', 'post'], 'currencies/default/{id}', [ADMIN\AcnooCurrencyController::class, 'default'])->name('currencies.default');
    Route::post('currencies/delete-all', [ADMIN\AcnooCurrencyController::class,'deleteAll'])->name('currencies.delete-all');
    Route::get('currencies-excel', [ADMIN\AcnooCurrencyController::class, 'exportExcel'])->name('currencies.excel');
    Route::get('currencies-csv', [ADMIN\AcnooCurrencyController::class, 'exportCsv'])->name('currencies.csv');

    // Messages
    Route::resource('messages', ADMIN\AcnooMessageController::class)->only('index', 'destroy');
    Route::post('messages/filter', [ADMIN\AcnooMessageController::class, 'acnooFilter'])->name('messages.filter');
    Route::post('messages/delete-all', [ADMIN\AcnooMessageController::class, 'deleteAll'])->name('messages.delete-all');

    // Front CMS
    Route::resource('website-settings',ADMIN\AcnooWebSettingController::class);

     // Term And Condition Controller
    Route::resource('term-conditions', ADMIN\AcnooTermConditionController::class)->only('index', 'store');

    // Privacy And Policy Controller
    Route::resource('privacy-policy', ADMIN\AcnooPrivacyPloicyController::class)->only('index', 'store');

    Route::resource('testimonials', ADMIN\AcnooTestimonialController::class)->except('show');
    Route::post('testimonials/filter', [ADMIN\AcnooTestimonialController::class, 'acnooFilter'])->name('testimonials.filter');
    Route::post('testimonials/delete-all', [ADMIN\AcnooTestimonialController::class, 'deleteAll'])->name('testimonials.delete-all');

    Route::resource('features',ADMIN\AcnooFeatureController::class);
    Route::post('features/filter', [ADMIN\AcnooFeatureController::class, 'acnooFilter'])->name('features.filter');
    Route::post('features/status/{id}', [ADMIN\AcnooFeatureController::class,'status'])->name('features.status');
    Route::post('features/delete-all', [ADMIN\AcnooFeatureController::class, 'deleteAll'])->name('features.delete-all');

    Route::resource('interfaces',ADMIN\AcnooInterfaceController::class);
    Route::post('interfaces/filter', [ADMIN\AcnooInterfaceController::class, 'acnooFilter'])->name('interfaces.filter');
    Route::post('interfaces/status/{id}', [ADMIN\AcnooInterfaceController::class,'status'])->name('interfaces.status');
    Route::post('interfaces/delete-all', [ADMIN\AcnooInterfaceController::class, 'deleteAll'])->name('interfaces.delete-all');

    Route::resource('blogs', ADMIN\AcnooBlogController::class);
    Route::post('blogs/filter', [ADMIN\AcnooBlogController::class, 'acnooFilter'])->name('blogs.filter');
    Route::post('blogs/status/{id}', [ADMIN\AcnooBlogController::class,'status'])->name('blogs.status');
    Route::post('blogs/delete-all', [ADMIN\AcnooBlogController::class, 'deleteAll'])->name('blogs.delete-all');
    Route::get('blogs/comments/{id}', [ADMIN\AcnooBlogController::class, 'filterComment'])->name('blogs.filter.comment');

    Route::resource('comments', ADMIN\AcnooCommentController::class);
    Route::post('comments/delete-all', [ADMIN\AcnooCommentController::class, 'deleteAll'])->name('comments.delete-all');
    Route::post('comments/filter', [ADMIN\AcnooCommentController::class, 'acnooFilter'])->name('comments.filter');

    Route::resource('faqs', ADMIN\AcnooFaqController::class)->except('show');
    Route::post('faqs/delete-all', [ADMIN\AcnooFaqController::class, 'deleteAll'])->name('faqs.delete-all');
    Route::post('faqs/filter', [ADMIN\AcnooFaqController::class, 'acnooFilter'])->name('faqs.filter');
    Route::post('faqs/status/{id}', [ADMIN\AcnooFaqController::class,'status'])->name('faqs.status');

    // Notifications manager
    Route::prefix('notifications')->controller(ADMIN\NotificationController::class)->name('notifications.')->group(function () {
        Route::get('/', 'mtIndex')->name('index');
        Route::get('/{id}', 'mtView')->name('mtView');
        Route::get('view/all/', 'mtReadAll')->name('mtReadAll');
    });
});
