<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';
    protected $fillable = ['player_id'];

    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }
}