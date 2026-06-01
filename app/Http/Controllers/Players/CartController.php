<?php

namespace App\Http\Controllers\Players;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 


class CartController extends Controller
{
    private function getCartId() {
        // Lấy ID của người chơi đang đăng nhập
        $playerId = Auth::guard('player')->id();
        $cart = Cart::firstOrCreate(['player_id' => $playerId]);
        return $cart->id;
    }

    public function index() {
        $cartItems = CartItem::with('version.game')
            ->where('cart_id', $this->getCartId())
            ->get();
        return view('Players.cart.index', compact('cartItems'));
    }

    public function add($versionId) {
        $cartId = $this->getCartId();
        $item = CartItem::where('cart_id', $cartId)
            ->where('game_version_id', $versionId)
            ->first();

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
        // Kiểm tra xem item này có thực sự thuộc về giỏ hàng của người đang đăng nhập không
        $item = CartItem::where('id', $itemId)
            ->where('cart_id', $this->getCartId())
            ->firstOrFail();
            
        $item->update(['quantity' => $request->quantity]);
        return redirect()->back();
    }

    public function remove($itemId) {
        // Kiểm tra sở hữu trước khi xóa
        CartItem::where('id', $itemId)
            ->where('cart_id', $this->getCartId())
            ->firstOrFail()
            ->delete();
            
        return redirect()->back();
    }
}