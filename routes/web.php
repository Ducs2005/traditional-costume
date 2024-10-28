<?php
use App\Http\Controllers\MessageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


Route::get('/home', function () {
    return view('home');
});

Route::get('/product-list', function () {
    return view('product.product_list');
});

Route::get('/product_type', function () {
    return view('product.product_type');
});

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

Route::get('/view_cart', function () {
    return view('view_cart');
});
Route::get('/api/products', [ProductController::class, 'getProducts']);
Route::get('/product_description/{id}', [ProductController::class, 'show'])->name('product.description');


Route::middleware(['auth'])->group(function () {
    Route::get('/fetch-messages/{userId}', [MessageController::class, 'fetchMessages']);
});


