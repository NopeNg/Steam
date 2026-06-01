<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $table = 'games';
    public $timestamps = false;
    protected $fillable = ['name', 'release_date', 'description', 'cover_image', 'publisher', 'developer', 'status', 'requirements'];

    // Quan hệ 1 - nhiều với Hình ảnh đi kèm
    public function images()
    {
        return $this->hasMany(GameImage::class, 'game_id');
    }

    // Quan hệ nhiều - nhiều với Thể loại (Category)
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'game_categories', 'game_id', 'category_id');
    }

    // Quan hệ 1 - nhiều với các Phiên bản bán hàng (Standard, Deluxe...)
    public function versions()
    {
        return $this->hasMany(GameVersion::class, 'game_id');
    }
}