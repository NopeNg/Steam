<?php
namespace App\Providers;
use Illuminate\Support\Facades\View; 
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\ServiceProvider; // Đừng quên import lớp ServiceProvider gốc

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        View::composer('*', function ($view) {
        $cartCount = 0;
        
        // Chỉ đếm nếu người chơi đã đăng nhập
        if (Auth::guard('player')->check()) {
            $cart = Cart::where('player_id', Auth::guard('player')->id())->first();
            if ($cart) {
                // Đếm tổng quantity của các item
                $cartCount = CartItem::where('cart_id', $cart->id)->sum('quantity');
            }
        }
        
        $view->with('cartCount', $cartCount);
    });

    }
}
