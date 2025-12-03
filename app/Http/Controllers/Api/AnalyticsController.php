<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function revenueKpi()
    {
        $today = now()->format('Y-m-d');
        $yesterday = now()->subDay()->format('Y-m-d');

        $todayRevenue = Invoice::whereDate('created_at', $today)->sum('total_amount');
        $yesterdayRevenue = Invoice::whereDate('created_at', $yesterday)->sum('total_amount');
        $trend = $yesterdayRevenue > 0 
            ? round((($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100, 1)
            : 0;

        $nhifCovered = Invoice::sum('nhif_covered');
        $patientCopay = Invoice::sum('patient_copay');

        return response()->json([
            'today_revenue' => round($todayRevenue),
            'trend_percent' => $trend,
            'total_nhif_covered' => round($nhifCovered),
            'total_patient_copay' => round($patientCopay),
            'total_inpatients' => \App\Models\Patient::where('is_admitted', 1)->count(),
        ]);
    }
}