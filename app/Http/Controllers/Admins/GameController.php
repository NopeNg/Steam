<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\GameImage;
use App\Models\CartItem;

class GameController extends Controller
{
    private ActivityLogService $activityLog;

    public function __construct(ActivityLogService $activityLog)
    {
        $this->activityLog = $activityLog;
    }

    public function index(Request $request)
    {
        $query = Game::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $games = $query->orderBy('id', 'desc')->paginate(10);

        return view('Admins.games.index', compact('games'));
    }

    public function create()
    {
        return view('Admins.games.create');
    }

    public function store(Request $request)
    {
        $data = $request->only([
            'name', 'publisher', 'developer', 'description', 'requirements', 'status', 'release_date'
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'developer' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'status' => 'required|in:Active,Inactive,ComingSoon,Archived',
            'release_date' => 'nullable|date',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ], [
            'name.required' => 'Tên game không được để trống.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'cover_image.image' => 'Ảnh bìa phải là file ảnh.',
            'gallery_images.*.image' => 'Ảnh gallery phải là file ảnh.',
        ]);

        // TỰ ĐỘNG: Nếu release_date lớn hơn hôm nay → chuyển status thành ComingSoon
        if (!empty($data['release_date'])) {
            $releaseDate = \Carbon\Carbon::parse($data['release_date'])->startOfDay();
            $today = \Carbon\Carbon::today();
            if ($releaseDate->gt($today)) {
                $data['status'] = 'ComingSoon';
            }
        }

        // Tạo game mới
        $game = Game::create($data);

        // Xử lý cover image - vừa upload file vừa nhập URL
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $path = $file->store('games', 'public');
            $game->update(['cover_image' => '/storage/' . $path]);
        } elseif ($request->filled('cover_image_url')) {
            $game->update(['cover_image' => $request->cover_image_url]);
        }

        // Xử lý gallery images - vừa upload file vừa nhập URL
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('gallery', 'public');
                    GameImage::create([
                        'game_id' => $game->id,
                        'image_path' => '/storage/' . $path,
                        'image_type' => 'Screenshot',
                        'game_part' => 'Gameplay'
                    ]);
                }
            }
        }
        
        // Xử lý gallery URLs
        if ($request->filled('gallery_urls')) {
            foreach ($request->gallery_urls as $url) {
                if (!empty(trim($url))) {
                    GameImage::create([
                        'game_id' => $game->id,
                        'image_path' => trim($url),
                        'image_type' => 'Screenshot',
                        'game_part' => 'Gameplay'
                    ]);
                }
            }
        }

        $this->activityLog->log('Thêm game mới', 'Đã thêm game "' . $game->name . '" (ID: ' . $game->id . ')');

        return redirect()->route('admin.games.index')->with('success', 'Đã thêm mới Sản phẩm thành công!');
    }

    public function show($id)
    {
        $game = Game::with('images')->findOrFail($id);
        return view('Admins.games.show', compact('game'));
    }

    public function edit($id)
    {
        $game = Game::with('images')->findOrFail($id);
        return view('Admins.games.edit', compact('game'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'developer' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'status' => 'required|in:Active,Inactive,ComingSoon,Archived',
            'release_date' => 'nullable|date',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ], [
            'name.required' => 'Tên game không được để trống.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'cover_image.image' => 'Ảnh bìa phải là file ảnh.',
            'gallery_images.*.image' => 'Ảnh gallery phải là file ảnh.',
        ]);

        $game = Game::findOrFail($id);

        $data = $request->only([
            'name', 'publisher', 'developer', 'description', 'requirements', 'status', 'release_date'
        ]);

        // TỰ ĐỘNG: Nếu release_date lớn hơn hôm nay → ép status thành ComingSoon
        if (!empty($data['release_date'])) {
            $releaseDate = \Carbon\Carbon::parse($data['release_date'])->startOfDay();
            $today = \Carbon\Carbon::today();
            if ($releaseDate->gt($today)) {
                $data['status'] = 'ComingSoon';
            }
        }

        $game->update($data);

        // TỰ ĐỘNG QUÉT SẠCH GIỎ HÀNG KHI ADMIN CHUYỂN THÀNH 'Inactive'
        if ($game->status === 'Inactive') {
            $versionIds = $game->versions()->pluck('id');
            CartItem::whereIn('game_version_id', $versionIds)->delete();
        }

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $path = $file->store('games', 'public');
            $game->update(['cover_image' => '/storage/' . $path]);
        } elseif ($request->filled('cover_image_url')) {
            $game->update(['cover_image' => $request->cover_image_url]);
        }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('gallery', 'public');
                    GameImage::create([
                        'game_id' => $game->id,
                        'image_path' => '/storage/' . $path,
                        'image_type' => 'Screenshot',
                        'game_part' => 'Gameplay'
                    ]);
                }
            }
        }
        
        // Xử lý gallery URLs
        if ($request->filled('gallery_urls')) {
            foreach ($request->gallery_urls as $url) {
                if (!empty(trim($url))) {
                    GameImage::create([
                        'game_id' => $game->id,
                        'image_path' => trim($url),
                        'image_type' => 'Screenshot',
                        'game_part' => 'Gameplay'
                    ]);
                }
            }
        }

        $this->activityLog->log('Cập nhật game', 'Đã cập nhật game "' . $game->name . '" (ID: ' . $game->id . '), trạng thái: ' . $game->status);

        return redirect()->route('admin.games.index')->with('success', 'Cập nhật sản phẩm và đồng bộ giỏ hàng thành công!');
    }

    public function destroy($id)
    {
        try {
            $game = Game::findOrFail($id);
            $gameName = $game->name;
            $game->delete();

            $this->activityLog->log('Xóa game', 'Đã xóa game "' . $gameName . '" (ID: ' . $id . ')');

            return redirect()->back()->with('success', 'Đã xóa sản phẩm thành công!');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == "23000") {
                return redirect()->back()->with('error', 'Bảo vệ Dữ liệu: Không thể xóa Game đã phát sinh giao dịch. Vui lòng "Chỉnh sửa" và đổi trạng thái thành "Tạm ẩn"!');
            }

            return redirect()->back()->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }

    public function destroyImage($id)
    {
        $image = GameImage::findOrFail($id);
        $gameId = $image->game_id;

        if ($image->image_path && file_exists(public_path($image->image_path))) {
            unlink(public_path($image->image_path));
        }

        $image->delete();

        $this->activityLog->log('Xóa ảnh game', 'Đã xóa ảnh (ID: ' . $id . ') của game (ID: ' . $gameId . ')');

        return redirect()->back();
    }
}