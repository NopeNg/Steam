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
    PlayerAuthController,
    RedeemController
};

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/api/home-updates', [HomeController::class, 'checkUpdates']);
Route::get('/games', [GameController::class, 'index'])->name('games.index');
Route::get('/games/{id}', [GameController::class, 'show'])->name('games.show');
Route::get('/api/games-list-updates', [GameController::class, 'checkListUpdates']);
Route::get('/api/games-detail-updates/{id}', [GameController::class, 'checkDetailUpdates']);

Route::get('/register', [PlayerAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [PlayerAuthController::class, 'register']);
Route::get('/login', [PlayerAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [PlayerAuthController::class, 'login']);
Route::post('/logout', [PlayerAuthController::class, 'logout'])->name('logout');
// Route cho các trang chính sách và điều khoản
// Bỏ Route::group đi để các route nằm ở gốc website
Route::get('/terms', function () {
    return view('Players.terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('Players.privacy');
})->name('privacy');

Route::get('/refund', function () {
    return view('Players.refund-policy');
})->name('refund');

Route::middleware(['auth.player','check.banned'])->group(function () {
// kiểm tra trạng thái
Route::get('/api/check-status', function() {
        return response()->json(['status' => Auth::guard('player')->user()->status]);
    });
Route::post('/cart/validate', [App\Http\Controllers\Players\CartController::class, 'validateCart'])
         ->name('cart.validate');
         

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
        Route::post('/{order_id}/execute', [App\Http\Controllers\Players\OrderController::class, 'executeOrder'])->name('orders.execute');
    });

  Route::prefix('library')->group(function () {
    Route::get('/', [LibraryController::class, 'index'])->name('library.index');
    Route::get('/redeem', [RedeemController::class, 'redeemView'])->name('library.redeem');
    Route::post('/redeem', [RedeemController::class, 'activate'])->name('library.redeem.post');
    Route::post('/activate', [RedeemController::class, 'activate'])->name('library.activate');
});


    Route::prefix('social')->group(function () {
        Route::get('/', [SocialController::class, 'index'])->name('social.index');
        Route::get('/friends', [SocialController::class, 'friendsIndex'])->name('friends.index');
        Route::get('/friends/search', [SocialController::class, 'searchFriend'])->name('friends.search');
        Route::post('/friends/send-request/{id}', [SocialController::class, 'sendRequest'])->name('friends.request');
        Route::post('/friends/accept/{id}', [SocialController::class, 'acceptRequest'])->name('friends.accept');
        Route::delete('/friends/remove/{id}', [SocialController::class, 'removeFriend'])->name('friends.remove');
    });

    Route::prefix('social/gifts')->group(function () {
        Route::get('/', [GiftController::class, 'index'])->name('gifts.index');
        Route::get('/send/{friend_id}', [GiftController::class, 'showSendForm'])->name('gifts.showSendForm');
        Route::post('/accept/{id}', [GiftController::class, 'accept'])->name('gifts.accept');
        Route::post('/reject/{id}', [GiftController::class, 'reject'])->name('gifts.reject');
        Route::post('/send', [GiftController::class, 'send'])->name('gifts.send');
    });
});

// === ADMIN ROUTES ===
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', [\App\Http\Controllers\Admins\AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Admins\AdminAuthController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\Admins\AdminAuthController::class, 'logout'])->name('logout');

    // === PROTECTED ADMIN ROUTES ===
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
            Route::get('/{id}/revoked-games', [\App\Http\Controllers\Admins\PlayerController::class, 'revokedGames'])->name('revoked');
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
            Route::get('/create', [\App\Http\Controllers\Admins\KeyController::class, 'create'])->name('custom.create');
            Route::post('/custom', [\App\Http\Controllers\Admins\KeyController::class, 'storeCustom'])->name('custom.store');
            Route::post('/{id}/revoke', [\App\Http\Controllers\Admins\KeyController::class, 'revoke'])->name('revoke');
        });

        // === REPORTS ===
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admins\ReportController::class, 'index'])->name('index');
            Route::get('/export', [\App\Http\Controllers\Admins\ReportController::class, 'export'])->name('export');
        });

        // === ACTIVITY LOGS ===
        Route::prefix('activity-logs')->name('activity-logs.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admins\ActivityLogController::class, 'index'])->name('index');
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
            Route::post('/mapping/bulk-update', [\App\Http\Controllers\Admins\SupplierController::class, 'mappingBulkUpdate'])->name('mapping.bulk-update');
        });

    }); // End middleware admin
}); // End admin prefix

// Chatbot route
Route::post('/chat', [\App\Http\Controllers\Players\ChatController::class, 'chat']);
