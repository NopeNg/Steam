<?php

namespace App\Http\Controllers\Players;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CartController extends Controller implements HasMiddleware
{

public static function middleware(): array
    {
        return [
            new Middleware('auth.player'),
            new Middleware('check.banned'),
        ];
    }
    // Hàm lấy hoặc tạo giỏ hàng cho người chơi hiện tại
    private function getCartId() {
        // Lấy ID của người chơi đang đăng nhập
        $playerId = Auth::guard('player')->id();
        $cart = Cart::firstOrCreate(['player_id' => $playerId]);
        return $cart->id;
    }

    // Hiển thị giỏ hàng
    public function index() {
        $cartItems = CartItem::with('version.game')
            ->where('cart_id', $this->getCartId())
            ->get();
        return view('Players.cart.index', compact('cartItems'));
    }

    // Thêm game vào giỏ hàng
    public function add($versionId) {
        // Kiểm tra game version tồn tại và game đang Active
        $version = \App\Models\GameVersion::with('game')->find($versionId);
        if (!$version) {
            return redirect()->back()->with('error', 'Phiên bản game không tồn tại.');
        }
        if (!$version->game || $version->game->status !== 'Active') {
            return redirect()->back()->with('error', 'Game này hiện không khả dụng để mua.');
        }

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
public function validateCart()
{
    $cartId = $this->getCartId();
    $items = CartItem::with('version.game')->where('cart_id', $cartId)->get();
    $wasChanged = false;

    foreach ($items as $item) {
        // Kiểm tra trạng thái game từ model version -> game
        if ($item->version->game->status !== 'Active') {
            $item->delete();
            $wasChanged = true;
        }
    }

    return response()->json(['changed' => $wasChanged]);
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