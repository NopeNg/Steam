<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameImage extends Model
{
    // Chỉ định chính xác tên bảng trong Database
    protected $table = 'game_images';

    // Bảng này không dùng 2 cột timestamps mặc định
    public $timestamps = false;

    // Các cột cho phép ghi dữ liệu hàng loạt
    protected $fillable = [
        'game_id',
        'image_path',
        'image_type',
        'game_part'
    ];

    /**
     * Mối quan hệ ngược lại: Một hình ảnh thuộc về một Game gốc
     */
    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }
}