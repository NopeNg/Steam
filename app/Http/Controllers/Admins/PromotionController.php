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
            'campaign_name' => 'required|string|max:255',
            'discount_percent' => 'required|numeric|min:1|max:100',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ], [
            'campaign_name.required' => 'Tên chiến dịch không được để trống.',
            'discount_percent.required' => 'Mức giảm giá không được để trống.',
            'discount_percent.min' => 'Mức giảm giá tối thiểu là 1%.',
            'discount_percent.max' => 'Mức giảm giá tối đa là 100%.',
            'start_time.required' => 'Thời gian bắt đầu không được để trống.',
            'end_time.required' => 'Thời gian kết thúc không được để trống.',
            'end_time.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
        ]);

        $promotion = Promotion::create($request->only([
            'campaign_name', 'discount_percent', 'start_time', 'end_time'
        ]));

        // Cập nhật discount_price cho tất cả game_versions (tính toán giá sau giảm)
        \App\Models\GameVersion::all()->each(function($version) use ($promotion) {
            $discountedPrice = $version->price * (1 - $promotion->discount_percent / 100);
            $version->update([
                'promotion_id' => $promotion->id,
                'discount_price' => round($discountedPrice, 2)
            ]);
        });

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
            'campaign_name' => 'required|string|max:255',
            'discount_percent' => 'required|numeric|min:1|max:100',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ], [
            'campaign_name.required' => 'Tên chiến dịch không được để trống.',
            'discount_percent.required' => 'Mức giảm giá không được để trống.',
            'discount_percent.min' => 'Mức giảm giá tối thiểu là 1%.',
            'discount_percent.max' => 'Mức giảm giá tối đa là 100%.',
            'start_time.required' => 'Thời gian bắt đầu không được để trống.',
            'end_time.required' => 'Thời gian kết thúc không được để trống.',
            'end_time.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
        ]);
        $promotion = Promotion::findOrFail($id);
        $promotion->update($request->only([
            'campaign_name', 'discount_percent', 'start_time', 'end_time'
        ]));

        // Cập nhật lại discount_price cho tất cả game_versions với mức giảm mới
        \App\Models\GameVersion::where('promotion_id', $id)->each(function($version) use ($promotion) {
            $discountedPrice = $version->price * (1 - $promotion->discount_percent / 100);
            $version->update([
                'discount_price' => round($discountedPrice, 2)
            ]);
        });

        return redirect()->route('admin.promotions.index')->with('success', 'Đã cập nhật chiến dịch khuyến mãi thành công!');
    }

    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        
        \App\Models\GameVersion::where('promotion_id', $id)->update([
            'promotion_id' => null,
            'discount_price' => null
        ]);

        $promotion->delete();

        return redirect()->back()->with('success', 'Đã xóa chiến dịch khuyến mãi thành công và cập nhật lại giá các phiên bản game!');
    }
}