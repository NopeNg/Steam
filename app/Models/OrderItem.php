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

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function gameVersion()
    {
        return $this->belongsTo(GameVersion::class, 'game_version_id');
    }

    public function version()
    {
        return $this->belongsTo(GameVersion::class, 'game_version_id');
    }

    public function gameKeys()
    {
        return $this->hasMany(GameKey::class, 'order_item_id');
    }
}
