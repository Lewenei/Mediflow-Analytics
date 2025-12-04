<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use App\Models\Patient;

class AnalyticsController extends Controller
{

    public function revenueKpi()
    {
        $today = now()->format('Y-m-d');
        $todayRevenue = Invoice::whereDate('created_at', $today)->sum('total_amount');
        $nhifTotal = Invoice::sum('nhif_covered');
        $copayTotal = Invoice::sum('patient_copay');

        return response()->json([
            'today_revenue' => round($todayRevenue),
            'total_nhif_covered' => round($nhifTotal),
            'total_patient_copay' => round($copayTotal),
            'active_inpatients' => Patient::where('is_admitted', 1)->count(),
            'total_patients' => Patient::count(),
            'total_invoices' => Invoice::count(),
        ]);
    }
}
