<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'game_version_id',
        'quantity',
        'price_at_purchase'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function gameVersion()
    {
        return $this->belongsTo(GameVersion::class, 'game_version_id');
    }
}