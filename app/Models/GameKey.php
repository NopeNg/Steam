<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameKey extends Model
{
    protected $table = 'game_keys';
    public $timestamps = false;
    protected $fillable = ['order_item_id', 'key_code', 'status', 'fetched_at', 'supplier_transaction_id'];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }
public function game()
{
    // Giả sử GameKey có khóa ngoại là game_id trỏ tới Model Game
    return $this->belongsTo(Game::class, 'game_id');
}
}