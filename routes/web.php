<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [DashboardController::class, 'guestDashboard'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/account-settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::put('/profile/account-settings', [ProfileController::class, 'updateSettings'])->name('profile.settings.update');
    Route::get('/profile/system-settings', [ProfileController::class, 'systemSettings'])->name('profile.system-settings');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

    // Notifications (shared by all roles)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');
    Route::get('/notifications/poll', [NotificationController::class, 'poll'])->name('notifications.poll');
});

Route::middleware(['auth', 'role:buyer'])->group(function () {
    Route::get('/buyer/dashboard', [DashboardController::class, 'buyerDashboard'])->name('buyer.dashboard');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/remove-selected', [CartController::class, 'removeSelected'])->name('cart.remove-selected');
    Route::get('/checkout', [CartController::class, 'showCheckout'])->name('cart.checkout.page');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    Route::get('/purchases', [DeliveryController::class, 'buyerPurchases'])->name('buyer.purchases.index');
    Route::get('/purchases/{delivery}', [DeliveryController::class, 'buyerPurchaseDetails'])->name('buyer.purchases.show');
});

Route::prefix('seller')->middleware(['auth', 'role:seller,admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'sellerDashboard'])->name('seller.dashboard');
    Route::get('/inventory', [InventoryController::class, 'index'])->name('seller.inventory.index');
    Route::get('/deliveries', [DeliveryController::class, 'index'])->name('seller.deliveries.index');
    Route::get('/deliveries/buyer/{buyer}', [DeliveryController::class, 'buyerDetails'])->name('seller.deliveries.buyer-details');
    Route::put('/deliveries/{delivery}/status', [DeliveryController::class, 'updateStatus'])->name('seller.deliveries.update-status');
    Route::post('/deliveries/{delivery}/assign-rider', [DeliveryController::class, 'assignRider'])->name('seller.deliveries.assign-rider');

    Route::get('/products', [ProductController::class, 'sellerIndex'])->name('seller.products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('seller.products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('seller.products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('seller.products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('seller.products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('seller.products.destroy');

    Route::get('/announcements/create', [AnnouncementController::class, 'create'])->name('seller.announcements.create');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('seller.announcements.store');
});

// Rider routes
Route::prefix('rider')->middleware(['auth', 'role:rider'])->group(function () {
    Route::get('/dashboard', [RiderController::class, 'dashboard'])->name('rider.dashboard');
    Route::get('/deliveries', [RiderController::class, 'deliveries'])->name('rider.deliveries');
    Route::get('/deliveries/{delivery}', [RiderController::class, 'showDelivery'])->name('rider.deliveries.show');
    Route::post('/deliveries/{delivery}/pickup', [RiderController::class, 'pickUp'])->name('rider.deliveries.pickup');
    Route::post('/deliveries/{delivery}/on-the-way', [RiderController::class, 'onTheWay'])->name('rider.deliveries.on-the-way');
    Route::post('/deliveries/{delivery}/delivered', [RiderController::class, 'delivered'])->name('rider.deliveries.delivered');
});
