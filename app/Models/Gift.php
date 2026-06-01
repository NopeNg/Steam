<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    protected $table = 'gifts';
    public $timestamps = false;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'game_key_id',
        'message',
        'status'
    ];

    // Người tặng quà
    public function sender()
    {
        return $this->belongsTo(Player::class, 'sender_id');
    }

    // Người nhận quà
    public function receiver()
    {
        return $this->belongsTo(Player::class, 'receiver_id');
    }

    // Mã Key số đi kèm với món quà này
    public function gameKey()
    {
        return $this->belongsTo(GameKey::class, 'game_key_id');
    }

    // app/Models/Gift.php
public function game()
{
    return $this->belongsTo(GameKey::class, 'game_key_id')->with('game');
}

protected $casts = [
    'created_at' => 'datetime',
];
}