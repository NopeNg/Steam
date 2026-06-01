<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Players\{
    HomeController, GameController, CartController, 
    OrderController, LibraryController, SocialController, GiftController, PlayerAuthController
};

// ==========================================
// 1. TRANG CÔNG KHAI (Không cần đăng nhập)
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/games', [GameController::class, 'index'])->name('games.index');
Route::get('/games/{id}', [GameController::class, 'show'])->name('games.show');

// Route Đăng ký / Đăng nhập
Route::get('/register', [PlayerAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [PlayerAuthController::class, 'register']);
Route::get('/login', [PlayerAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [PlayerAuthController::class, 'login']);
Route::post('/logout', [PlayerAuthController::class, 'logout'])->name('logout');


// ==========================================
// 2. KHU VỰC CÁ NHÂN (Bảo mật bởi Middleware 'auth.player')
// ==========================================
Route::middleware(['auth.player'])->group(function () {

    // A. GIỎ HÀNG
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/add/{versionId}', [CartController::class, 'add'])->name('cart.add');
        Route::post('/update/{itemId}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/remove/{itemId}', [CartController::class, 'remove'])->name('cart.remove');
    });

    // B. ĐƠN HÀNG & THANH TOÁN
    Route::prefix('orders')->group(function () {
        Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
        Route::post('/checkout/process', [OrderController::class, 'process'])->name('orders.process');
        Route::get('/history', [OrderController::class, 'history'])->name('orders.history');
        Route::get('/waiting/{order_id}', [OrderController::class, 'waiting'])->name('orders.waiting');
        Route::get('/{id}', [OrderController::class, 'detail'])->name('orders.detail');
        // Route này không cần middleware nếu dùng cho callback từ VNPay
        Route::get('/vnpay-return', [OrderController::class, 'vnpayReturn'])->name('vnpay.return');
    });

    // C. THƯ VIỆN GAME
    Route::prefix('library')->group(function () {
        Route::get('/', [LibraryController::class, 'index'])->name('library.index');
        Route::get('/redeem', [LibraryController::class, 'redeemView'])->name('library.redeem');
        Route::post('/redeem', [LibraryController::class, 'activate'])->name('library.redeem.post');
        Route::post('/activate', [LibraryController::class, 'activate'])->name('library.activate');
    });

// D. BẠN BÈ
Route::prefix('social')->group(function () {
    Route::get('/', [SocialController::class, 'index'])->name('social.index');
    Route::get('/friends', [SocialController::class, 'friendsIndex'])->name('friends.index');
    
    // Đổi post thành get để fix lỗi 405 Method Not Allowed
    Route::get('/friends/search', [SocialController::class, 'searchFriend'])->name('friends.search');
    
    Route::post('/friends/send-request/{id}', [SocialController::class, 'sendRequest'])->name('friends.request');
    
    // Bổ sung các route cho Chấp nhận và Xóa bạn bè (đã làm trong Controller ở bước trước)
    Route::post('/friends/accept/{id}', [SocialController::class, 'acceptRequest'])->name('friends.accept');
    Route::delete('/friends/remove/{id}', [SocialController::class, 'removeFriend'])->name('friends.remove');
});

// E. QUÀ TẶNG
Route::prefix('social/gifts')->group(function () {
    // 1. Hiển thị danh sách quà nhận được
    Route::get('/', [GiftController::class, 'index'])->name('gifts.index');
    
    // 2. Hiển thị Form gửi quà cho một người bạn cụ thể
    // Đặt route này trước để tránh bị trùng với các route có tham số ID phía sau
    Route::get('/send/{friend_id}', [GiftController::class, 'showSendForm'])->name('gifts.showSendForm');
    
    // 3. Xử lý logic gửi quà (POST)
    Route::post('/send', [GiftController::class, 'send'])->name('gifts.send');
    
    // 4. Xử lý nhận hoặc từ chối quà
    Route::post('/accept/{id}', [GiftController::class, 'accept'])->name('gifts.accept');
    Route::post('/reject/{id}', [GiftController::class, 'reject'])->name('gifts.reject');
});
});