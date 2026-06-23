<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $table = 'promotions';
    public $timestamps = false;

    protected $fillable = [
        'campaign_name',
        'discount_percent',
        'start_time',
        'end_time'
    ];
}