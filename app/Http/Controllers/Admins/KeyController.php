<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GameKey;

class KeyController extends Controller
{
    public function index(Request $request)
    {
        $query = GameKey::with('orderItem.gameVersion.game');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('key_code', 'like', '%' . $search . '%')
                ->orWhereHas('orderItem.gameVersion.game', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $keys = $query->orderBy('id', 'desc')->paginate(15);

        return view('Admins.keys.index', compact('keys'));
    }

    public function storeCustom(Request $request)
    {
        $request->validate([
            'key_code' => 'required|unique:game_keys,key_code',
            'note' => 'nullable',
        ]);

        GameKey::create([
            'key_code' => trim($request->key_code),
            'status' => 'Giveaway',
            'supplier_transaction_id' => $request->note,
            'fetched_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Đã tạo mã Key Giveaway thành công!');
    }
}