<?php

use App\Http\Controllers\AccessTimeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PusherController;
use App\Http\Controllers\ForgotPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\TypeController;

Route::get('/', function () {
    return view('home');
});

Route::get('/product-list', function () {
    return view('product.product_list');
});

Route::get('/product-list', [ProductController::class, 'productList'])->name('products.list');

Route::get('/product_type', function () {
    return view('product.product_type');
});

Route::get('/login', function () {
    return view('account.login');
})->name('login');

Route::group(['prefix' => 'account'], function() {

    // Guest middleware
    Route::group(['middleware' => 'guest'], function() {
        Route::get('login', [LoginController::class, 'index'])->name('account.login');
        Route::get('register', [LoginController::class, 'register'])->name('account.register');
        Route::post('process-register', [LoginController::class, 'processRegister'])->name('account.processRegister');
        Route::post('authenticate', [LoginController::class, 'authenticate'])->name('account.authenticate');

    });

    // Authentiated middleware
    Route::group(['middleware' => 'auth'], function() {
        Route::get('logout', [LoginController::class, 'logout'])->name('account.logout');
        Route::get('home', [LoginController::class, 'home'])->name('account.home');
    });

});

Route::get('profile', [LoginController::class, 'profile'])->name('account.profile');

Route::get('/view_cart', [CartController::class, 'viewCart'])->name('cart.view');

Route::get('/api/products', [ProductController::class, 'getProducts']);
Route::post('api/products/filter', [ProductController::class, 'filterProducts']);

Route::get('/product_description/{id}', [ProductController::class, 'show'])->name('product.description');


Route::middleware(['auth'])->group(function () {
    Route::get('/fetch-messages/{userId}', [MessageController::class, 'fetchMessages']);
});


Route::prefix('admin')->middleware('checkadminrole')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');

    Route::get('/types', [TypeController::class, 'index'])->name('admin.types.index');
    Route::get('/types/create', [TypeController::class, 'create'])->name('admin.types.create');
    Route::post('/types', [TypeController::class, 'store'])->name('admin.types.store');
    Route::get('/types/{id}/edit', [TypeController::class, 'edit'])->name('admin.types.edit');
    Route::put('/types/{id}', [TypeController::class, 'update'])->name('admin.types.update');
    Route::delete('/types/{id}', [TypeController::class, 'destroy'])->name('admin.types.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/administrators', [AdminController::class, 'create'])->name('admin.administrators.index');
    Route::post('/administrators', [AdminController::class, 'store'])->name('admin.administrators.store');
    Route::get('/administrators/show', [AdminController::class, 'show'])->name('admin.administrators.show');
    Route::get('/administrators/{id}/edit', [AdminController::class, 'edit'])->name('admin.administrators.edit');
    Route::put('/administrators/{id}', [AdminController::class, 'update'])->name('admin.administrators.update');
    Route::delete('/administrators/{id}', [AdminController::class, 'destroy'])->name('admin.administrators.destroy');
});

Route::post('/send-message', [MessageController::class, 'sendMessage'])->middleware('auth');

Route::post('/send-message', [MessageController::class, 'sendMessage'])->middleware('auth');
Route::get('/forgot_password', [ForgotPasswordController::class, 'forgotpass']);
Route::post('/reset_pwd', [ForgotPasswordController::class, 'reset_pwd']);

Route::middleware(['auth'])->group(function () {
    Route::get('api/pusher-config', function () {
        return response()->json([
            'key' => config('broadcasting.connections.pusher.key'), // Pusher Key
            'cluster' => config('broadcasting.connections.pusher.options.cluster'), // Pusher Cluster
        ]);
    });
});

Route::get('/new_pwd', [ForgotPasswordController::class, 'new_pwd']);
Route::post('/reset_new_pwd', [ForgotPasswordController::class, 'reset_new_pwd']);


Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/accessTime', [AccessTimeController::class, 'index'])->name('accessTime');

Route::get('/contact-seller/{sellerId}', [MessageController::class, 'contactSeller'])->name('contact.seller');


Route::delete('/cart/remove/{cartItem}', [CartController::class, 'removeItem'])->name('cart.removeItem');
Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
