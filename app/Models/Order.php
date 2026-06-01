<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    
    // Tắt timestamps mặc định của Laravel vì bảng này chỉ dùng duy nhất cột created_at tự động
    public $timestamps = false;

    protected $fillable = [
        'player_id',
        'handled_by_admin',
        'total_amount',
        'order_type',
        'status',
        'payment_method'
    ];

    protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];
    // Mối quan hệ với các dòng chi tiết sản phẩm trong đơn
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    // Đơn hàng này thuộc về ai mua
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }
}