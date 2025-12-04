<?php

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Router;

/** @var Router $router */

// Welcome message
$router->get('/', function () {
    return response()->json([
        'project'  => 'MediFlow Hospital Analytics',
        'version'  => '1.0',
        'status'   => 'running',
        'message'  => 'Hospital Intelligence Platform – Empowering Healthcare with Data-Driven Insights',
        'endpoints' => [
            'GET    /api/patients',
            'GET    /api/patients/{id}',
            'POST   /api/patients',
            'GET    /api/invoices',
            'GET    /api/invoices/{id}',
            'GET    /api/invoices/{id}/pdf',
            'GET    /api/analytics/revenue-kpi (coming soon)',
        ],
        'demo' => 'https://mediflow-analytics.onrender.com',
    ]);
});

$router->group(['prefix' => 'api'], function ($router) {

    // Patients
    $router->get('patients', 'Api\PatientController@index');
    $router->post('patients', 'Api\PatientController@store');
    $router->get('patients/{patient}', 'Api\PatientController@show');

    // Invoices + PDF
    $router->get('invoices', 'Api\InvoiceController@index');
    $router->get('invoices/{invoice}', 'Api\InvoiceController@show');
    $router->get('invoices/{invoice}/pdf', 'Api\InvoiceController@pdf');

    // Analytics
    $router->get('analytics/revenue-kpi', 'Api\AnalyticsController@revenueKpi');

    // Pharmacy
    $router->get('drugs', 'Api\DrugController@index');
    $router->get('drugs/low-stock', 'Api\DrugController@lowStock');
    $router->get('dispenses', 'Api\DispenseController@index');

    // Debug
    $router->get('debug', function () {
        return [
            "status" => "API ALIVE",
            "patients" => \App\Models\Patient::count(),
            "invoices" => \App\Models\Invoice::count(),
            "drugs" => \App\Models\Drug::count(),
        ];
    });
});

