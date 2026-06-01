<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promotion;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $query = Promotion::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('campaign_name', 'like', '%' . $search . '%');
        }

        $promotions = $query->orderBy('id', 'desc')->paginate(15);

        return view('Admins.promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('Admins.promotions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'campaign_name' => 'required',
            'discount_percent' => 'required|numeric|min:1|max:100',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        Promotion::create($request->only([
            'campaign_name', 'discount_percent', 'start_time', 'end_time'
        ]));

        return redirect()->route('admin.promotions.index')->with('success', 'Đã thêm mới chiến dịch khuyến mãi thành công!');
    }

    public function edit($id)
    {
        $promotion = Promotion::findOrFail($id);
        return view('Admins.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'campaign_name' => 'required',
            'discount_percent' => 'required|numeric|min:1|max:100',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $promotion = Promotion::findOrFail($id);
        $promotion->update($request->only([
            'campaign_name', 'discount_percent', 'start_time', 'end_time'
        ]));

        return redirect()->route('admin.promotions.index')->with('success', 'Đã cập nhật chiến dịch khuyến mãi thành công!');
    }

    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();

        return redirect()->back()->with('success', 'Đã xóa chiến dịch khuyến mãi thành công!');
    }
}