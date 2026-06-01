<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cart_items';
    public $timestamps = false;
    protected $fillable = ['cart_id', 'game_version_id', 'quantity'];

    public function version()
    {
        return $this->belongsTo(GameVersion::class, 'game_version_id');
    }
}