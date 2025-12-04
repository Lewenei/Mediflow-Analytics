<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispense extends Model
{
    protected $fillable = [
        'patient_id', 'drug_id', 'quantity', 'total_cost', 'dispensed_by'
    ];

    protected $dates = ['dispensed_at'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function drug()
    {
        return $this->belongsTo(Drug::class);
    }
}