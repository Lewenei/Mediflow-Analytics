<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\Invoice;
use App\Models\InvoiceItem;

class InvoicesSeeder extends Seeder
{
    public function run(): void
    {
        // Hospital service codes (NHIF-approved)
        $services = [
            ['Consultation (General)', 'CONS001', 800],
            ['Consultation (Specialist)', 'CONS002', 1500],
            ['Normal Delivery', 'DELIV001', 15000],
            ['Caesarean Section', 'DELIV002', 45000],
            ['Antenatal Visit', 'ANC001', 1000],
            ['Postnatal Visit', 'PNC001', 800],
            ['Blood Test (Full Haemogram)', 'LAB001', 1200],
            ['Malaria Test', 'LAB002', 800],
            ['HIV Test', 'LAB003', 1000],
            ['Ultrasound', 'RAD001', 3000],
            ['X-Ray', 'RAD002', 2500],
            ['Antibiotics Injection', 'PHARM001', 1500],
            ['IV Fluids', 'PHARM002', 2000],
            ['Bed Charge (General Ward)', 'WARD001', 3000],
            ['Bed Charge (Private)', 'WARD002', 8000],
            ['Theatre Fee', 'SURG001', 20000],
            ['Anaesthesia', 'SURG002', 15000],
        ];

        $patients = Patient::inRandomOrder()->take(250)->get();

        foreach ($patients as $patient) {
            $itemCount = rand(3, 10);
            $total = 0;
            $nhifCovered = 0;

            $invoice = Invoice::create([
                'patient_id' => $patient->id,
                'invoice_number' => 'INV-' . now()->format('Y') . '-' . str_pad(Invoice::count() + 1, 5, '0', STR_PAD_LEFT),
                'total_amount' => 0,
                'nhif_covered' => 0,
                'patient_copay' => 0,
                'status' => ['pending', 'submitted', 'approved', 'paid', 'rejected'][array_rand([0,1,2,3,4])],
                'nhif_submitted_at' => now()->subDays(rand(1, 30)),
            ]);

            for ($i = 0; $i < $itemCount; $i++) {
                $service = $services[array_rand($services)];
                $qty = rand(1, 4);
                $lineTotal = $service[2] * $qty;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => $service[0],
                    'nhif_code' => $service[1],
                    'quantity' => $qty,
                    'unit_price' => $service[2],
                    'total' => $lineTotal,
                ]);

                $total += $lineTotal;
            }

            // NHIF covers 60–98% depending on service
            $nhifPercentage = rand(60, 98) / 100;
            $nhifCovered = $total * $nhifPercentage;
            $copay = $total - $nhifCovered;

            $invoice->update([
                'total_amount' => $total,
                'nhif_covered' => round($nhifCovered, 2),
                'patient_copay' => round($copay, 2),
            ]);
        }

        $this->command->info('250+ hospital invoices seeded successfully!');
    }
}