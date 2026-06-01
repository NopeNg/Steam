<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierProvider extends Model
{
    protected $table = 'supplier_providers';

    protected $fillable = [
        'name',
        'code',
        'base_url',
        'api_key',
        'api_key_header',
        'timeout',
        'priority',
        'headers',
        'purchase_endpoint',
        'verify_endpoint',
        'status',
        'notes',
    ];

    protected $casts = [
        'headers' => 'json',
        'timeout' => 'integer',
        'priority' => 'integer',
    ];

    public function gameMappings()
    {
        return $this->hasMany(GameSupplierMapping::class, 'supplier_provider_id');
    }

    public function games()
    {
        return $this->belongsToMany(Game::class, 'game_supplier_mappings', 'supplier_provider_id', 'game_id')
            ->withPivot('supplier_game_id', 'status')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }
}