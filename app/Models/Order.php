<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'player_id',
        'handled_by_admin',
        'total_amount',
        'order_type',
        'status',
        'payment_method'
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}