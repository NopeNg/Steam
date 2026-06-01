<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    // Chỉ định chính xác tên bảng trong Database (vì Laravel mặc định sẽ tìm số nhiều 'libraries')
    protected $table = 'library';

    // Bảng này không có 2 cột created_at và updated_at mặc định của Laravel
    public $timestamps = false;

    // Các cột cho phép ghi dữ liệu hàng loạt (Mass Assignment)
    protected $fillable = [
        'player_id',
        'game_key_id',
        'purchased_at'
    ];

    // Ép kiểu dữ liệu cho cột ngày tháng để tiện định dạng khi hiển thị ra View
    protected $casts = [
        'purchased_at' => 'datetime',
    ];

    /**
     * Mối quan hệ: Một bản ghi thư viện thuộc về một Người chơi (Player)
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    /**
     * Mối quan hệ: Một bản ghi thư viện sở hữu một Mã Key số (GameKey)
     */
    public function gameKey()
    {
        return $this->belongsTo(GameKey::class, 'game_key_id');
    }
    public function game()
{
    return $this->belongsTo(Game::class, 'game_key_id');
}
}