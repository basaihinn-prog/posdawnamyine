<?php

use App\Models\Option;
use App\Http\Controllers as Web;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', [Web\WebController::class, 'index'])->name('home');
Route::resource('blogs', Web\BlogController::class)->only('index', 'show', 'store');
Route::get('/filter-blogs-by-tag', [Web\BlogController::class, 'filterBlogsByTag'])->name('blogs.tag.filter');
Route::get('/about-us', [Web\AboutController::class, 'index'])->name('about.index');
Route::get('/plans', [Web\PlanController::class, 'index'])->name('plan.index');

// Payment Routes Start
Route::get('/payments-gateways/{plan_id}/{business_id}', [Web\PaymentController::class, 'index'])->name('payments-gateways.index');
Route::post('/payments/{plan_id}/{gateway_id}', [Web\PaymentController::class, 'payment'])->name('payments-gateways.payment');
Route::get('/payment/success', [Web\PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/failed', [Web\PaymentController::class, 'failed'])->name('payment.failed');
Route::get('/order-status', [Web\PaymentController::class, 'orderStatus'])->name('order.status');

Route::get('/terms-conditions', [Web\TermServiceController::class, 'index'])->name('term.index');
Route::get('/privacy-policy', [Web\PolicyController::class, 'index'])->name('policy.index');

Route::get('/contact-us', [Web\ContactController::class, 'index'])->name('contact.index');
Route::post('/contact/store', [Web\ContactController::class, 'store'])->name('contact.store');

Route::group([
    'namespace' => 'App\Library',
], function () {
    Route::get('/payment/mollie', 'Mollie@status');
    Route::post('/payment/paystack', 'Paystack@status')->name('paystack.status');
    Route::get('/paystack', 'Paystack@view')->name('paystack.view');
    Route::get('/razorpay/payment', 'Razorpay@view')->name('razorpay.view');
    Route::post('/razorpay/status', 'Razorpay@status');
    Route::get('/payment/flutterwave', 'Flutterwave@status');
    Route::get('/payment/thawani', 'Thawani@status');
    Route::get('/payment/instamojo', 'Instamojo@status');
    Route::get('/payment/toyyibpay', 'Toyyibpay@status');
    Route::get('/tap-payment/status', 'TapPayment@status')->name('tap-payment.status');
});
// Payment Routes End

Route::get('/update', function () {
    try {

        Artisan::call('migrate');

        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        // Update version
        Option::updateOrCreate(
            ['key' => 'version'],
            ['value' => '3.1']
        );

        return back()->with('message', __('Updated'));
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/cache-clear', function () {

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');

    return back()->with('message', __('Cache has been cleared.'));
});

Route::get('/reset', function () {
    Artisan::call('migrate:fresh', ['--seed' => true]);
    return 'success';
});

require __DIR__ . '/auth.php';
