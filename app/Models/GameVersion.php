<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameVersion extends Model
{
    protected $table = 'game_versions';

    public $timestamps = false;

    protected $fillable = [
        'game_id', 
        'promotion_id', 
        'version_name', 
        'price', 
        'discount_price'
    ];

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'promotion_id');
    }
}