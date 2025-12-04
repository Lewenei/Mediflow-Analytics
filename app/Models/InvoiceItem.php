<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id', 'description', 'nhif_code',
        'quantity', 'unit_price', 'total'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}