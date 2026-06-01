<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\GameImage;

class GameController extends Controller
{
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
        $request->validate([
            'name' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'developer' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
            'release_date' => 'nullable|date',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery_types.*' => 'nullable|string',
            'gallery_parts.*' => 'nullable|string',
        ], [
            'name.required' => 'Tên game không được để trống.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'cover_image.image' => 'Ảnh bìa phải là file ảnh.',
            'gallery_images.*.image' => 'Ảnh gallery phải là file ảnh.',
        ]);

        $game = Game::create($request->only([
            'name',
            'publisher',
            'developer',
            'description',
            'requirements',
            'status',
            'release_date'
        ]));

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('games', 'public');
            $game->update(['cover_image' => '/storage/' . $path]);
        }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $index => $file) {
                if ($file->isValid()) {
                    $path = $file->store('gallery', 'public');
                    GameImage::create([
                        'game_id' => $game->id,
                        'image_path' => '/storage/' . $path,
                        'image_type' => $request->gallery_types[$index],
                        'game_part' => $request->gallery_parts[$index]
                    ]);
                }
            }
        }

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
            'status' => 'required|in:Active,Inactive',
            'release_date' => 'nullable|date',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery_types.*' => 'nullable|string',
            'gallery_parts.*' => 'nullable|string',
        ], [
            'name.required' => 'Tên game không được để trống.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'cover_image.image' => 'Ảnh bìa phải là file ảnh.',
            'gallery_images.*.image' => 'Ảnh gallery phải là file ảnh.',
        ]);

        $game = Game::findOrFail($id);

        $game->update($request->only([
            'name',
            'publisher',
            'developer',
            'description',
            'requirements',
            'status',
            'release_date'
        ]));

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('games', 'public');
            $game->update(['cover_image' => '/storage/' . $path]);
        }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $index => $file) {
                if ($file->isValid()) {
                    $path = $file->store('gallery', 'public');
                    GameImage::create([
                        'game_id' => $game->id,
                        'image_path' => '/storage/' . $path,
                        'image_type' => $request->gallery_types[$index],
                        'game_part' => $request->gallery_parts[$index]
                    ]);
                }
            }
        }

        return redirect()->route('admin.games.index');
    }

    public function destroy($id)
    {
        try {
            $game = Game::findOrFail($id);
            $game->delete();

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

        if ($image->image_path && file_exists(public_path($image->image_path))) {
            unlink(public_path($image->image_path));
        }

        $image->delete();

        return redirect()->back();
    }
}