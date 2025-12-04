<?php

use Laravel\Lumen\Routing\Router;

/** @var Router $router */

// Welcome message at /api (because of prefix in bootstrap/app.php)
$router->get('/', function () {
    return response()->json([
        'project'  => 'MediFlow Hospital Analytics',
        'version'  => '1.0',
        'status'   => 'running',
        'message'  => 'Hospital Intelligence Platform – Ready',
        'endpoints' => [
            '/api/patients',
            '/api/invoices',
            '/api/invoices/{id}/pdf',
            '/api/drugs',
            '/api/drugs/low-stock',
            '/api/dispenses',
            '/api/analytics/revenue-kpi',
            '/api/debug',
        ],
    ]);
});

// ALL ROUTES — NO PREFIX HERE ANYMORE!
$router->get('patients', 'Api\PatientController@index');
$router->post('patients', 'Api\PatientController@store');
$router->get('patients/{patient}', 'Api\PatientController@show');

$router->get('invoices', 'Api\InvoiceController@index');
$router->get('invoices/{invoice}', 'Api\InvoiceController@show');
$router->get('invoices/{invoice}/pdf', 'Api\InvoiceController@pdf');

$router->get('analytics/revenue-kpi', 'Api\AnalyticsController@revenueKpi');

$router->get('drugs', 'Api\DrugController@index');
$router->get('drugs/low-stock', 'Api\DrugController@lowStock');

$router->get('dispenses', 'Api\DispenseController@index');

$router->get('debug', function () {
    return response()->json([
        'status' => 'API LIVE',
        'patients' => \App\Models\Patient::count(),
        'invoices' => \App\Models\Invoice::count(),
        'drugs' => \App\Models\Drug::count(),
        'dispenses' => \App\Models\Dispense::count(),
    ]);
});