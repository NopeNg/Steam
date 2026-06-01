<?php

namespace App\Http\Controllers\Players;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getCartId() {
        // Giả lập Player có ID = 1
        $cart = Cart::firstOrCreate(['player_id' => 1]);
        return $cart->id;
    }

    public function index() {
        $cartItems = CartItem::with('version.game')->where('cart_id', $this->getCartId())->get();
        return view('Players.cart.index', compact('cartItems'));
    }

    public function add($versionId) {
        $cartId = $this->getCartId();
        $item = CartItem::where('cart_id', $cartId)->where('game_version_id', $versionId)->first();

        if ($item) {
            $item->increment('quantity');
        } else {
            CartItem::create([
                'cart_id' => $cartId,
                'game_version_id' => $versionId,
                'quantity' => 1
            ]);
        }
        return redirect()->route('cart.index')->with('success', 'Đã thêm vào giỏ hàng!');
    }

    public function update(Request $request, $itemId) {
        $item = CartItem::findOrFail($itemId);
        $item->update(['quantity' => $request->quantity]);
        return redirect()->back();
    }

    public function remove($itemId) {
        CartItem::findOrFail($itemId)->delete();
        return redirect()->back();
    }
}