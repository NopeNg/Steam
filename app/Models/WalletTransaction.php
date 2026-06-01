<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $table = 'wallet_transactions';

    public $timestamps = false;

    protected $fillable = [
        'player_id',
        'amount',
        'transaction_code',
        'status',
        'created_at',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }
}