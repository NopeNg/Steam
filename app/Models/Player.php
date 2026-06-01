<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Player extends Authenticatable
{
    use Notifiable;

    protected $table = 'players';

    protected $fillable = [
        'username', 'email', 'password', 'fullname', 'status'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}