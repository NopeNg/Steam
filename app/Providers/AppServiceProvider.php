<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer('*', function ($view) {
            $cartCount = 0;
            
            if (Auth::guard('player')->check()) {
                $cart = Cart::where('player_id', Auth::guard('player')->id())->first();
                if ($cart) {
                    $cartCount = CartItem::where('cart_id', $cart->id)->sum('quantity');
                }
            }
            
            $view->with('cartCount', $cartCount);
        });
    }
}