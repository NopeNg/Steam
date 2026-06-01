<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    public $timestamps = false;
    protected $fillable = ['category_name'];

    // Quan hệ nhiều - nhiều với Game
    public function games()
    {
        return $this->belongsToMany(Game::class, 'game_categories', 'category_id', 'game_id');
    }
}