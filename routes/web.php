<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\BlogController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\ProductsController;
use App\Http\Controllers\Backend\ProductCategoryController;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\Backend\OrdersController;
use App\Http\Controllers\Backend\ReplyChatController;
use App\Http\Controllers\Backend\RevenueController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\SettingsController;
use App\Http\Controllers\Fontend\ShopController;
use App\Http\Controllers\Fontend\ChatController;
use App\Http\Controllers\Fontend\CategoryController;
use App\Http\Controllers\Fontend\ContactController;
use App\Http\Controllers\Fontend\ViewBlogController;
use App\Http\Controllers\Fontend\AuthFontendController;
use App\Http\Controllers\Fontend\CartController;
use App\Http\Controllers\Fontend\CheckoutController;
use App\Http\Controllers\Fontend\ProductDetailController;
use App\Http\Controllers\Fontend\RegisterController;
use App\Http\Controllers\Fontend\SubscribeController;
use App\Http\Controllers\Fontend\FavoriteController;
use App\Http\Middleware\AuthenticateMiddleware;


/* Route Backend */

/* Trang chủ */
Route::get('dashboard/layout', [DashboardController::class, 'layout'])->name('dashboard.layout')->middleware(AuthenticateMiddleware::class);

/* Đăng kí / nhập / xuất */
Route::get('admin', [AuthController::class, 'admin'])->name('auth.admin');
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');

/* Tài khoản */
Route::get('users/index', [UsersController::class, 'index'])->name('users.index')->middleware(AuthenticateMiddleware::class);
Route::get('users/create', [UsersController::class, 'create'])->name('users.create')->middleware(AuthenticateMiddleware::class);
Route::post('users', [UsersController::class, 'store'])->name('users.store')->middleware(AuthenticateMiddleware::class);
Route::get('users/edit/{id}', [UsersController::class, 'edit'])->name('users.edit')->middleware(AuthenticateMiddleware::class);
Route::post('users/update/{id}', [UsersController::class, 'update'])->name('users.update')->middleware(AuthenticateMiddleware::class);
Route::delete('users/delete/{id}', [UsersController::class, 'delete'])->name('users.delete')->middleware(AuthenticateMiddleware::class);

/* Bài viết */ 
Route::get('blog/index', [BlogController::class, 'index'])->name('blog.index')->middleware(AuthenticateMiddleware::class);
Route::get('blog/create', [BlogController::class, 'create'])->name('blog.create')->middleware(AuthenticateMiddleware::class);
Route::post('blog', [BlogController::class, 'store'])->name('blog.store')->middleware(AuthenticateMiddleware::class);
Route::get('blog/edit/{id}', [BlogController::class, 'edit'])->name('blog.edit')->middleware(AuthenticateMiddleware::class);
Route::post('blogy/update/{id}', [BlogController::class, 'update'])->name('blog.update')->middleware(AuthenticateMiddleware::class);
Route::delete('blog/delete/{id}', [BlogController::class, 'delete'])->name('blog.delete')->middleware(AuthenticateMiddleware::class);

/* Danh mục sản phẩm */
Route::get('productcategory/index', [ProductCategoryController::class, 'index'])->name('productcategory.index')->middleware(AuthenticateMiddleware::class);
Route::get('productcategory/create', [ProductCategoryController::class, 'create'])->name('productcategory.create')->middleware(AuthenticateMiddleware::class);
Route::post('productcategory', [ProductCategoryController::class, 'store'])->name('productcategory.store')->middleware(AuthenticateMiddleware::class);
Route::get('productcategory/edit/{id}', [ProductCategoryController::class, 'edit'])->name('productcategory.edit')->middleware(AuthenticateMiddleware::class);
Route::post('productcategory/update/{id}', [ProductCategoryController::class, 'update'])->name('productcategory.update')->middleware(AuthenticateMiddleware::class);
Route::delete('productcategory/delete/{id}', [ProductCategoryController::class, 'delete'])->name('productcategory.delete')->middleware(AuthenticateMiddleware::class);

/* Sản phẩm */
Route::get('products/index', [ProductsController::class, 'index'])->name('products.index')->middleware(AuthenticateMiddleware::class);
Route::get('products/create', [ProductsController::class, 'create'])->name('products.create')->middleware(AuthenticateMiddleware::class);
Route::post('products', [ProductsController::class, 'store'])->name('products.store')->middleware(AuthenticateMiddleware::class);
Route::get('products/edit/{id}', [ProductsController::class, 'edit'])->name('products.edit')->middleware(AuthenticateMiddleware::class);
Route::post('products/update/{id}', [ProductsController::class, 'update'])->name('products.update')->middleware(AuthenticateMiddleware::class);
Route::delete('products/delete/{id}', [ProductsController::class, 'delete'])->name('products.delete')->middleware(AuthenticateMiddleware::class);

/* Đơn hàng */ 
Route::get('orders/index', [OrdersController::class, 'index'])->name('orders.index')->middleware(AuthenticateMiddleware::class);
Route::get('oders/edit/{id}', [OrdersController::class, 'edit'])->name('orders.edit')->middleware(AuthenticateMiddleware::class);
Route::post('orders/update/{id}', [OrdersController::class, 'update'])->name('orders.update')->middleware(AuthenticateMiddleware::class);
Route::delete('oders/delete/{id}', [OrdersController::class, 'delete'])->name('orders.delete')->middleware(AuthenticateMiddleware::class);

/* Thống kế */
Route::get('revenue/index',[RevenueController::class, 'index'])->name('revenue.index')->middleware(AuthenticateMiddleware::class);

/* ChatBox*/ 
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Hiển thị danh sách cuộc trò chuyện
    Route::get('/chat', [ReplyChatController::class, 'index'])->name('chat.index');

    // API tải tin nhắn
    Route::get('/chat/load', [ReplyChatController::class, 'load'])->name('chat.load');

    // API gửi tin nhắn
    Route::post('/chat/reply', [ReplyChatController::class, 'reply'])->name('chat.reply');

    // Reset trạng thái tin nhắn
    Route::post('/chat/seen', [ReplyChatController::class, 'markAsSeen'])->name('chat.seen');
});

/* Xem thông tin */
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index')->middleware(AuthenticateMiddleware::class);

/* Chỉnh sửa thông tin */
Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index')->middleware(AuthenticateMiddleware::class);
Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update')->middleware(AuthenticateMiddleware::class);
Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update')->middleware(AuthenticateMiddleware::class);



/* Route Fontend */

/* Trang chủ */ 
Route::get('shop', [ShopController::class, 'index'])->name('shop.index');
/* Danh mục sản phẩm */
Route::get('category', [CategoryController::class, 'index'])->name('category.index');
/* Blog */
Route::get('viewblog', [ViewBlogController::class, 'index'])->name('viewblog.index');
/* Liên hệ */
Route::get('contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact/send', [ContactController::class, 'sendContact'])->name('contact.send');

/* Dăng nhập/ đăng xuất */
Route::get('login', [AuthFontendController::class, 'index'])->name('login.index');
Route::post('authfontend.login', [AuthFontendController::class, 'login'])->name('authfontend.login');
Route::get('authfontend.logout', [AuthFontendController::class, 'logout'])->name('authfontend.logout');
/* Đăng kí */ 
Route::get('index', [RegisterController::class, 'index'])->name('register.index');
Route::post('register', [RegisterController::class, 'register'])->name('register');
/* Chi tiết sẩn phẩm */
Route::get('product/{id}',[ProductDetailController::class, 'index'])->name('productdetail.index');
/* Giỏ hàng */
Route::get('cart', [CartController::class, 'index'])->name('cart.index');
Route::post('cart/add', [CartController::class, 'addCart'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('cart/delete/{id}', [CartController::class, 'delete'])->name('cart.delete');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

/* Thanh toán */ 
Route::get('checkout',[CheckoutController::class, 'index'])->name('checkout.index');
Route::post('checkout', [CheckoutController::class, 'checkout'])->name('checkout')->middleware('auth');
Route::post('/checkout/momo', [CheckoutController::class, 'momo_payment'])->name('checkout.momo_payment');
Route::get('confirm',[CheckoutController::class, 'confirm'])->name('checkout.confirm'); 
/*ChatBox*/

Route::get('/chat', [ChatController::class, 'index']);
Route::get('/chat/load', [ChatController::class, 'load']);
Route::post('/chat/send', [ChatController::class, 'send']);
/* Đăng kí thành viên */
Route::post('/subscribe', [SubscribeController::class, 'store'])->name('subscribe.store');
/* Yêu thích sản phẩm */
Route::get('/favorite', [FavoriteController::class, 'index'])->name('favorite.index');
Route::get('/favorite/list', [FavoriteController::class, 'list'])->name('favorite.list');
Route::post('/favorite/toggle', [FavoriteController::class, 'toggle'])->name('favorite.toggle');