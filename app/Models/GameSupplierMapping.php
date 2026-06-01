<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameSupplierMapping extends Model
{
    protected $table = 'game_supplier_mappings';

    protected $fillable = [
        'game_id',
        'supplier_provider_id',
        'supplier_game_id',
        'status',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function supplierProvider()
    {
        return $this->belongsTo(SupplierProvider::class, 'supplier_provider_id');
    }
}