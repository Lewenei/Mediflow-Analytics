<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'id_number', 'phone', 'gender',
        'date_of_birth', 'nhif_number', 'ward', 'is_admitted'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'admitted_at'   => 'datetime',
        'discharged_at' => 'datetime',
        'is_admitted'   => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($patient) {
            $patient->hospital_number = 'MED-' . now()->format('Y') . '-' .
                str_pad(Patient::count() + 1, 6, '0', STR_PAD_LEFT);
            
            if ($patient->is_admitted) {
                $patient->admitted_at = now();
            }
        });
    }

    public function fullName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}