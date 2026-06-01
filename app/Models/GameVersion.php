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

    /**
     * Mối quan hệ: Một phiên bản thuộc về một Game gốc
     */
    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    /**
     * Mối quan hệ: Một phiên bản có thể áp dụng một chương trình khuyến mãi (Promotion)
     */
    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'promotion_id');
    }
}