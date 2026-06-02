<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Players\{
    HomeController,
    GameController,
    CartController,
    OrderController,
    LibraryController,
    SocialController,
    GiftController,
    PlayerAuthController
};

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/games', [GameController::class, 'index'])->name('games.index');
Route::get('/games/{id}', [GameController::class, 'show'])->name('games.show');

Route::get('/register', [PlayerAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [PlayerAuthController::class, 'register']);
Route::get('/login', [PlayerAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [PlayerAuthController::class, 'login']);
Route::post('/logout', [PlayerAuthController::class, 'logout'])->name('logout');

Route::middleware(['auth.player'])->group(function () {

    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/add/{versionId}', [CartController::class, 'add'])->name('cart.add');
        Route::post('/update/{itemId}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/remove/{itemId}', [CartController::class, 'remove'])->name('cart.remove');
    });

    Route::prefix('orders')->group(function () {
        Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
        Route::post('/checkout/process', [OrderController::class, 'process'])->name('orders.process');
        Route::get('/history', [OrderController::class, 'history'])->name('orders.history');
        Route::get('/waiting/{order_id}', [OrderController::class, 'waiting'])->name('orders.waiting');
        Route::get('/{id}', [OrderController::class, 'detail'])->name('orders.detail');
        Route::get('/vnpay-return', [OrderController::class, 'vnpayReturn'])->name('vnpay.return');
    });

    Route::prefix('library')->group(function () {
        Route::get('/', [LibraryController::class, 'index'])->name('library.index');
        Route::get('/redeem', [LibraryController::class, 'redeemView'])->name('library.redeem');
        Route::post('/redeem', [LibraryController::class, 'activate'])->name('library.redeem.post');
        Route::post('/activate', [LibraryController::class, 'activate'])->name('library.activate');
    });

    Route::prefix('social')->group(function () {
        Route::get('/', [SocialController::class, 'index'])->name('social.index');
        Route::get('/friends', [SocialController::class, 'friendsIndex'])->name('friends.index');
        Route::post('/friends/search', [SocialController::class, 'searchFriend'])->name('friends.search');
        Route::post('/friends/send-request/{id}', [SocialController::class, 'sendRequest'])->name('friends.request');
    });

    Route::prefix('social/gifts')->group(function () {
        Route::get('/', [GiftController::class, 'index'])->name('gifts.index');
        Route::post('/accept/{id}', [GiftController::class, 'accept'])->name('gifts.accept');
        Route::post('/send', [GiftController::class, 'send'])->name('gifts.send');
    });

});

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', [\App\Http\Controllers\Admins\AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Admins\AdminAuthController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\Admins\AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware(['admin'])->group(function () {

        Route::get('/dashboard', [\App\Http\Controllers\Admins\DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('games')->name('games.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admins\GameController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admins\GameController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admins\GameController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Admins\GameController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admins\GameController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admins\GameController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admins\GameController::class, 'destroy'])->name('destroy');
            Route::delete('/images/{id}', [\App\Http\Controllers\Admins\GameController::class, 'destroyImage'])->name('destroyImage');
        });

        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admins\OrderController::class, 'index'])->name('index');
            Route::get('/{id}', [\App\Http\Controllers\Admins\OrderController::class, 'show'])->name('show');
            Route::put('/{id}/status', [\App\Http\Controllers\Admins\OrderController::class, 'updateStatus'])->name('updateStatus');
            Route::post('/{id}/refund', [\App\Http\Controllers\Admins\OrderController::class, 'refund'])->name('refund');
            Route::post('/{id}/manual-key', [\App\Http\Controllers\Admins\OrderController::class, 'manualKey'])->name('manualKey');
        });

        Route::prefix('players')->name('players.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admins\PlayerController::class, 'index'])->name('index');
            Route::put('/{id}/toggle', [\App\Http\Controllers\Admins\PlayerController::class, 'toggleStatus'])->name('toggle');
        });

        Route::prefix('promotions')->name('promotions.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admins\PromotionController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admins\PromotionController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admins\PromotionController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admins\PromotionController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admins\PromotionController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admins\PromotionController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('keys')->name('keys.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admins\KeyController::class, 'index'])->name('index');
            Route::post('/custom', [\App\Http\Controllers\Admins\KeyController::class, 'storeCustom'])->name('custom.store');
        });

        Route::prefix('suppliers')->name('suppliers.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admins\SupplierController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admins\SupplierController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admins\SupplierController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admins\SupplierController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admins\SupplierController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admins\SupplierController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/health-check', [\App\Http\Controllers\Admins\SupplierController::class, 'healthCheck'])->name('healthCheck');
            Route::put('/{id}/toggle', [\App\Http\Controllers\Admins\SupplierController::class, 'toggleStatus'])->name('toggle');

            Route::get('/mapping/list', [\App\Http\Controllers\Admins\SupplierController::class, 'mappingIndex'])->name('mapping');
            Route::get('/mapping/create', [\App\Http\Controllers\Admins\SupplierController::class, 'mappingCreate'])->name('mapping.create');
            Route::post('/mapping', [\App\Http\Controllers\Admins\SupplierController::class, 'mappingStore'])->name('mapping.store');
            Route::get('/mapping/{id}/edit', [\App\Http\Controllers\Admins\SupplierController::class, 'mappingEdit'])->name('mapping.edit');
            Route::put('/mapping/{id}', [\App\Http\Controllers\Admins\SupplierController::class, 'mappingUpdate'])->name('mapping.update');
            Route::delete('/mapping/{id}', [\App\Http\Controllers\Admins\SupplierController::class, 'mappingDestroy'])->name('mapping.destroy');
        });

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