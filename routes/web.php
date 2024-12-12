<?php

use App\Http\Controllers\AccessTimeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\ForgotPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RatingController;

Route::get('/', [ProductController::class, 'product_home']);
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
Route::post('/update-avatar', [AccountController::class, 'updateAvatar'])->name('update-avatar');

Route::get('/view_cart', [CartController::class, 'viewCart'])->name('cart.view');
Route::get('/own_shop', [SellerController::class, 'viewShop'])->name('ownShop.view');

Route::get('/api/products', [ProductController::class, 'getProducts']);
Route::post('api/products/filter', [ProductController::class, 'filterProducts']);
Route::get('/category/{attribute}/{id}', [ProductController::class, 'showCategory'])->name('category.show');


Route::get('/product_description/{id}', [ProductController::class, 'show'])->name('product.description');


Route::middleware(['auth'])->group(function () {
    Route::get('/fetch-messages/{userId}', [MessageController::class, 'fetchMessages']);
});


Route::get('/admin', [AdminController::class, 'checkAuth'])->name('admin.checkAuth');
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login/request', [AdminController::class, 'login'])->name('login.request');
Route::get('/admin/forgotPassword', function() {
    return view('admin_views.forgotPassword');
})->name('admin.forgotPassword');
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
    Route::get('/admin/open-shop', [AdminController::class, 'openShop'])->name('admin.openShop');
    Route::get('/admin/user/{id}', [AdminController::class, 'getUserDetails'])->name('admin.getUserDetails');
    Route::get('/admin/accessTime', [AccessTimeController::class, 'index'])->name('admin.accessTime');

    Route::get('/admin/dashboard', [AccessTimeController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/table/user', [AdminController::class, 'showUsers'])->name('table.user');
    Route::get('/admin/table/product', [AdminController::class, 'showProducts'])->name('table.product');
    Route::get('/admin/table/payment', [AdminController::class, 'showPayment'])->name('table.payment');

    Route::put('/admin/table/user/update/{id}', [AdminController::class, 'updateUser'])->name('user.update');
    Route::get('/admin/table/user/destroy{{id}}', [AdminController::class, 'create'])->name('user.destroy');
    Route::get('/admin/table/user/edit', [AdminController::class, 'create'])->name('product.edit');
    Route::delete('/admin/table/product/destroy/{id}', [AdminController::class, 'deleteProduct'])->name('product.destroy');
    Route::put('admin/product/update', [AdminController::class, 'updateProduct'])->name('product.update');
    Route::get('/admin/table/order', [AdminController::class, 'showOrder'])->name('table.order');
    Route::get('/admin/table/order2', [AdminController::class, 'showOrder'])->name('order.update');
    Route::post('/admin/user/{userId}/accept-selling-right', [AdminController::class, 'acceptSellingRight']);
    Route::post('/admin/user/{userId}/reject-selling-right', [AdminController::class, 'rejectSellingRight']);
    Route::get('/admin/sendNotifications', [AdminController::class, 'sendNotifications'])->name('admin.sendNotifications');
    Route::post('/admin/notification/send', [AdminController::class, 'sendNotification'])->name('notifications.send');
    Route::get('/admin/gallery', [AdminController::class, 'showGallery'])->name('admin.gallery');


    Route::post('/colors', [GalleryController::class, 'storeColor'])->name('color.store');
    Route::post('/buttons', [GalleryController::class, 'storeButton'])->name('button.store');
    Route::post('/type', [GalleryController::class, 'storeType'])->name('type.store');
    Route::post('/material', [GalleryController::class, 'storeMaterial'])->name('material.store');
    // Routes for editing and updating records
    Route::put('/colors/{id}', [GalleryController::class, 'updateColor'])->name('color.update');
    Route::put('/buttons/{id}', [GalleryController::class, 'updateButton'])->name('button.update');
    Route::put('/materials/{id}', [GalleryController::class, 'updateMaterial'])->name('material.update');
    Route::put('/types/{id}', [GalleryController::class, 'updateType'])->name('type.update');

    Route::delete('/color/{id}', [GalleryController::class, 'destroyColor'])->name('color.destroy');
    Route::delete('/button/{id}', [GalleryController::class, 'destroyButton'])->name('button.destroy');
    Route::delete('/material/{id}', [GalleryController::class, 'destroyMaterial'])->name('material.destroy');
    Route::delete('/type/{id}', [GalleryController::class, 'destroyType'])->name('type.destroy');

    Route::get('/admin/banner', [AdminController::class, 'sendNotifications'])->name('admin.banner');
    Route::get('/admin/decorations', [AdminController::class, function(){
        return view('admin_views.decoration');
    }])->name('admin.decoration');
    Route::post('/decoration/update', [AdminController::class, 'updateDecorationImages'])->middleware('auth')->name('updateDecorationImages');

    


});

Route::post('/send-message', [MessageController::class, 'sendMessage'])->middleware('auth');

Route::post('/send-message', [MessageController::class, 'sendMessage'])->middleware('auth');
Route::get('/forgot_password', [ForgotPasswordController::class, 'forgotpass']);
Route::post('/reset_pwd', [ForgotPasswordController::class, 'reset_pwd']);


Route::get('/new_pwd', [ForgotPasswordController::class, 'new_pwd']);
Route::post('/reset_new_pwd', [ForgotPasswordController::class, 'reset_new_pwd']);
Route::get('/verify-email', [LoginController::class, 'verifyEmail'])->name('verify.email');


Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/accessTime', [AccessTimeController::class, 'index'])->name('accessTime');

Route::get('/contact-seller/{sellerId}', [MessageController::class, 'contactSeller'])->name('contact.seller');


Route::delete('/cart/remove/{cartItem}', [CartController::class, 'removeItem'])->name('cart.removeItem');
Route::delete('/cart/update/{cartItem}', [CartController::class, 'updateItem'])->name('cart.updateItem');
Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
Route::post('/cart/payment', [CartController::class, 'processPayment'])->name('payment.process');

Route::post('/seller-request', [SellerController::class, 'request'])->name('seller.request');
Route::delete('/shop/remove/{product}', [SellerController::class, 'remove'])->name('shop.remove');
Route::post('/reset-sold', [SellerController::class, 'resetSold'])->name('resetSold');
Route::post('/product/store', action: [SellerController::class, 'store'])->name('product.store');


Route::get('/view_order', [OrderController::class, 'index'])->name('viewOrder');
Route::get('/order-details/{orderId}', action: [OrderController::class, 'getOrder'])->name('order.detail');
Route::get('seller/view_order', [OrderController::class, 'seller_view'])->name('seller.viewOrder');
Route::get('seller/order-details/{orderId}', action: [OrderController::class, 'getOrderSeller'])->name('seller.order.detail');
Route::post('/orders/{order}/confirm', [OrderController::class, 'confirm'])->name('orders.confirm');
Route::post('/orders/{order}/confirm-received', [OrderController::class, 'confirmReceived'])->name('confirm.received');
Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

Route::post('/vnpay/createPayment', [PaymentController::class, 'createVnpayPayment'])->name('vnpay.createPayment');
Route::get('/payment/success', [CartController::class, 'clearCart'])->name('payment.success');

Route::get('/notification', [NotificationController::class, 'show'])->name('notification');
Route::post('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unreadCount');

Route::post('/rate-product', [RatingController::class, 'store'])->name('rate.product');
Route::post('seller/product/update/{id}', [SellerController::class, 'update'])->name('seller.update');
