<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'release_date',
        'description',
        'cover_image',
        'publisher',
        'developer',
        'requirements',
        'status',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function images()
    {
        return $this->hasMany(GameImage::class);
    }

    public function versions()
    {
        return $this->hasMany(GameVersion::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'game_categories');
    }
}