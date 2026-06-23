<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use App\Models\Promotion;
use App\Models\GameVersion;
use App\Models\Game;

class PromotionController extends Controller
{
    private ActivityLogService $activityLog;

    public function __construct(ActivityLogService $activityLog)
    {
        $this->activityLog = $activityLog;
    }

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
        // Lấy tất cả game kèm versions để hiển thị checkbox chọn
        $games = Game::with('versions')->orderBy('name')->get();
        return view('Admins.promotions.create', compact('games'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'campaign_name' => 'required|string|max:255',
            'discount_percent' => 'required|numeric|min:1|max:100',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'game_version_ids' => 'required|array|min:1',
            'game_version_ids.*' => 'exists:game_versions,id',
        ], [
            'campaign_name.required' => 'Tên chiến dịch không được để trống.',
            'discount_percent.required' => 'Mức giảm giá không được để trống.',
            'discount_percent.min' => 'Mức giảm giá tối thiểu là 1%.',
            'discount_percent.max' => 'Mức giảm giá tối đa là 100%.',
            'start_time.required' => 'Thời gian bắt đầu không được để trống.',
            'end_time.required' => 'Thời gian kết thúc không được để trống.',
            'end_time.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
            'game_version_ids.required' => 'Vui lòng chọn ít nhất một phiên bản game.',
            'game_version_ids.min' => 'Vui lòng chọn ít nhất một phiên bản game.',
            'game_version_ids.*.exists' => 'Phiên bản game không hợp lệ.',
        ]);

        $promotion = Promotion::create($request->only([
            'campaign_name', 'discount_percent', 'start_time', 'end_time'
        ]));

        // Cập nhật discount_price cho các game_versions được chọn
        GameVersion::whereIn('id', $request->game_version_ids)->each(function($version) use ($promotion) {
            $discountedPrice = $version->price * (1 - $promotion->discount_percent / 100);
            $version->update([
                'promotion_id' => $promotion->id,
                'discount_price' => round($discountedPrice, 2)
            ]);
        });

        $this->activityLog->log('Thêm khuyến mãi', 'Đã thêm chiến dịch "' . $promotion->campaign_name . '" (giảm ' . $promotion->discount_percent . '%) áp dụng cho ' . count($request->game_version_ids) . ' phiên bản');

        return redirect()->route('admin.promotions.index')->with('success', 'Đã thêm mới chiến dịch khuyến mãi thành công!');
    }

    public function edit($id)
    {
        $promotion = Promotion::findOrFail($id);
        // Lấy danh sách version IDs đang được áp dụng promotion này
        $appliedVersionIds = GameVersion::where('promotion_id', $id)->pluck('id')->toArray();
        $games = Game::with('versions')->orderBy('name')->get();
        return view('Admins.promotions.edit', compact('promotion', 'games', 'appliedVersionIds'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'campaign_name' => 'required|string|max:255',
            'discount_percent' => 'required|numeric|min:1|max:100',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'game_version_ids' => 'required|array|min:1',
            'game_version_ids.*' => 'exists:game_versions,id',
        ], [
            'campaign_name.required' => 'Tên chiến dịch không được để trống.',
            'discount_percent.required' => 'Mức giảm giá không được để trống.',
            'discount_percent.min' => 'Mức giảm giá tối thiểu là 1%.',
            'discount_percent.max' => 'Mức giảm giá tối đa là 100%.',
            'start_time.required' => 'Thời gian bắt đầu không được để trống.',
            'end_time.required' => 'Thời gian kết thúc không được để trống.',
            'end_time.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
            'game_version_ids.required' => 'Vui lòng chọn ít nhất một phiên bản game.',
            'game_version_ids.min' => 'Vui lòng chọn ít nhất một phiên bản game.',
            'game_version_ids.*.exists' => 'Phiên bản game không hợp lệ.',
        ]);
        
        $promotion = Promotion::findOrFail($id);
        $promotion->update($request->only([
            'campaign_name', 'discount_percent', 'start_time', 'end_time'
        ]));

        // Xóa promotion cũ khỏi tất cả versions trước
        GameVersion::where('promotion_id', $id)->update([
            'promotion_id' => null,
            'discount_price' => null
        ]);

        // Cập nhật discount_price cho các game_versions được chọn
        GameVersion::whereIn('id', $request->game_version_ids)->each(function($version) use ($promotion) {
            $discountedPrice = $version->price * (1 - $promotion->discount_percent / 100);
            $version->update([
                'promotion_id' => $promotion->id,
                'discount_price' => round($discountedPrice, 2)
            ]);
        });

        $this->activityLog->log('Cập nhật khuyến mãi', 'Đã cập nhật chiến dịch "' . $promotion->campaign_name . '" (ID: ' . $promotion->id . ')');

        return redirect()->route('admin.promotions.index')->with('success', 'Đã cập nhật chiến dịch khuyến mãi thành công!');
    }

    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotionName = $promotion->campaign_name;
        
        GameVersion::where('promotion_id', $id)->update([
            'promotion_id' => null,
            'discount_price' => null
        ]);

        $promotion->delete();

        $this->activityLog->log('Xóa khuyến mãi', 'Đã xóa chiến dịch "' . $promotionName . '" (ID: ' . $id . ')');

        return redirect()->back()->with('success', 'Đã xóa chiến dịch khuyến mãi thành công và cập nhật lại giá các phiên bản game!');
    }
}