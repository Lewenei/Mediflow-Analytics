<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
    protected $fillable = [
        'generic_name', 'brand_name', 'dosage_form', 'strength',
        'pack_size', 'unit_price', 'current_stock', 'reorder_level', 'is_narcotic'
    ];

    public function dispenses()
    {
        return $this->hasMany(Dispense::class);
    }
}