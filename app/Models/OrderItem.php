<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'game_version_id',
        'quantity',
        'price_at_purchase'
    ];

    // Thu thuộc về hóa đơn lớn nào
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Liên kết tới phiên bản game được mua để lấy Tên game/Giá game
    public function version()
    {
        return $this->belongsTo(GameVersion::class, 'game_version_id');
    }
}