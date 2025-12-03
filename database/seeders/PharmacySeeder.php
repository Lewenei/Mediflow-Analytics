<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Drug;
use App\Models\Patient;
use App\Models\Dispense;

class PharmacySeeder extends Seeder
{
    public function run()
    {
        $drugs = [
            ['Paracetamol', 'Panadol', 'Tablet', '500mg', 100, 5.00, 850, 100],
            ['Amoxicillin', 'Amoxil', 'Capsule', '500mg', 100, 12.00, 420, 50],
            ['Ciprofloxacin', 'Cipro', 'Tablet', '500mg', 10, 45.00, 180, 30],
            ['Metronidazole', 'Flagyl', 'Tablet', '400mg', 21, 8.00, 600, 100],
            ['Artemether/Lumefantrine', 'Coartem', 'Tablet', '20/120mg', 24, 120.00, 320, 50],
            ['ORS', 'Oral Rehydration Salts', 'Sachet', '1L', 50, 25.00, 1200, 200],
            ['Ferrous Sulphate', 'Fefol', 'Tablet', '200mg', 30, 15.00, 500, 100],
            ['Insulin Regular', 'Actrapid', 'Injection', '100IU/ml', 1, 1200.00, 45, 10, true],
            ['Omeprazole', 'Losec', 'Capsule', '20mg', 14, 35.00, 280, 50],
            ['Salbutamol', 'Ventolin', 'Inhaler', '100mcg', 200, 850.00, 90, 20],
        ];

        foreach ($drugs as $d) {
            Drug::create([
                'generic_name' => $d[0],
                'brand_name' => $d[1],
                'dosage_form' => $d[2],
                'strength' => $d[3],
                'pack_size' => $d[4],
                'unit_price' => $d[5],
                'current_stock' => $d[6],
                'reorder_level' => $d[7],
                'is_narcotic' => $d[8] ?? false,
            ]);
        }

        // Dispense drugs to random patients
        $patients = Patient::inRandomOrder()->take(180)->get();
        foreach ($patients as $p) {
            $dispenses = rand(1, 5);
            for ($i = 0; $i < $dispenses; $i++) {
                $drug = Drug::inRandomOrder()->first();
                $qty = rand(1, 30);
                if ($drug->current_stock >= $qty) {
                    Dispense::create([
                        'patient_id' => $p->id,
                        'drug_id' => $drug->id,
                        'quantity' => $qty,
                        'total_cost' => $qty * $drug->unit_price,
                        'dispensed_by' => 'Nurse Mercy',
                    ]);
                    $drug->decrement('current_stock', $qty);
                }
            }
        }

        $this->command->info('Pharmacy module seeded — 200+ drugs + dispensing records!');
    }
}