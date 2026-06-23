<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    
    public $timestamps = false;

    protected $fillable = [
        'player_id',
        'friend_id',
        'handled_by_admin',
        'total_amount',
        'order_type',
        'status',
        'payment_method'
    ];

    public function friend()
    {
        return $this->belongsTo(Player::class, 'friend_id');
    }

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}