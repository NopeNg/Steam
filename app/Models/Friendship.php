<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    protected $table = 'friendships';
    public $timestamps = false; // Chỉ sử dụng created_at mặc định của DB

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'status'
    ];

// Quan hệ với người gửi
    public function sender() {
        return $this->belongsTo(Player::class, 'sender_id');
    }

    // Quan hệ với người nhận
    public function receiver() {
        return $this->belongsTo(Player::class, 'receiver_id');
    }
}