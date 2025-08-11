<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Payment\StripeController;
use App\Http\Controllers\AddressController;

Route::get('/', function () {
    return view('welcome');
});

// ✅ Add this block
Route::get('/dashboard', function () {
    $user = auth()->user();

    if (!$user) return redirect()->route('login');

    if ($user->isAdmin())    return redirect()->route('admin.dashboard');
    if ($user->isChef())     return redirect()->route('chef.tasks');
    if ($user->isDelivery()) return redirect()->route('delivery.list');

    return redirect()->route('shop.index');
})->middleware(['auth'])->name('dashboard');

// auth routes from Breeze
require __DIR__ . '/auth.php';

// Customer area
Route::middleware(['auth', 'role:customer,admin'])->group(function () {
    Route::get('/shop',      [\App\Http\Controllers\ShopController::class, 'index'])->name('shop.index');
    Route::get('/shop/{product}', [\App\Http\Controllers\ShopController::class, 'show'])->name('shop.show');
    Route::get('/cart',      [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [\App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');
    Route::get('/checkout',  [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'place'])->name('checkout.place');
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [\App\Http\Controllers\OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/reviews',  [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');

    // Addresses
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::post('/addresses/{address}/default', [AddressController::class, 'setDefault'])->name('addresses.default');

    // Stripe
    Route::get('/pay/stripe/{order}', [StripeController::class, 'start'])->name('pay.stripe.start');
    Route::get('/pay/stripe/success', [StripeController::class, 'success'])->name('pay.stripe.success');
    Route::get('/pay/stripe/cancel', [StripeController::class, 'cancel'])->name('pay.stripe.cancel');
});

// Admin area
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except(['show']);
    Route::get('orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/assign-delivery', [\App\Http\Controllers\Admin\OrderController::class, 'assignDelivery'])->name('orders.assignDelivery');
    Route::post('orders/{order}/proceed-delivery', [\App\Http\Controllers\Admin\OrderController::class, 'proceedToDelivery'])->name('orders.proceedDelivery');
    Route::patch('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('orders/{order}/refund', [\App\Http\Controllers\Admin\OrderController::class, 'refund'])->name('orders.refund');
    Route::get('reports/sales', [\App\Http\Controllers\Admin\ReportController::class, 'sales'])->name('reports.sales');
    Route::get('reports/stock', [\App\Http\Controllers\Admin\ReportController::class, 'stock'])->name('reports.stock');
});

// Chef area
Route::middleware(['auth', 'role:chef,admin'])->prefix('chef')->name('chef.')->group(function () {
    Route::get('tasks', [\App\Http\Controllers\Chef\TaskController::class, 'index'])->name('tasks');
    Route::post('stock/{product}/increase', [\App\Http\Controllers\Chef\StockController::class, 'increase'])->name('stock.increase');
});

// Delivery area
Route::middleware(['auth', 'role:delivery,admin'])->prefix('delivery')->name('delivery.')->group(function () {
    Route::get('list', [\App\Http\Controllers\Delivery\DeliveryController::class, 'index'])->name('list');
    Route::post('orders/{order}/pickup', [\App\Http\Controllers\Delivery\DeliveryController::class, 'pickupOrder'])->name('pickup');
    Route::post('orders/{order}/delivered', [\App\Http\Controllers\Delivery\DeliveryController::class, 'markDelivered'])->name('delivered');
});
