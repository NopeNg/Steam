<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameKey extends Model
{
    protected $table = 'game_keys';
    public $timestamps = false;
    protected $fillable = ['order_item_id', 'game_version_id', 'key_code', 'status', 'fetched_at', 'supplier_transaction_id', 'supplier_code'];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function keyErrorReports()
    {
        return $this->hasMany(KeyErrorReport::class, 'game_key_id');
    }

    public function latestErrorReport()
    {
        return $this->hasOne(KeyErrorReport::class, 'game_key_id')->latestOfMany();
    }
}
