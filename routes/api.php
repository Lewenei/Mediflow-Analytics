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

// Standard API () Routes
$router->group(['prefix' => 'api'], function ($router) {

    // Patients Resource
    $router->group(['prefix' => 'patients'], function ($router) {
        $router->get('/', 'Api\PatientController@index');        // GET    /api/patients
        $router->post('/', 'Api\PatientController@store');       // POST   /api/patients
        $router->get('/{patient}', 'Api\PatientController@show'); // GET    /api/patients/123
        // TO-DO   Add PUT/PATCH /api/patients/{id} and DELETE
    });

    // Invoices Resource + PDF
    $router->get('invoices', 'Api\InvoiceController@index');                    // GET    /api/invoices
    $router->get('invoices/{invoice}', 'Api\InvoiceController@show');           // GET    /api/invoices/1
    $router->get('invoices/{invoice}/pdf', 'Api\InvoiceController@pdf');        // GET    /api/invoices/1/pdf

    // Analytics Routes
    $router->get('analytics/revenue-kpi', 'Api\AnalyticsController@revenueKpi'); // GET /api/analytics/revenue-kpi



    $router->get('drugs', 'Api\DrugController@index');
    $router->get('drugs/low-stock', 'Api\DrugController@lowStock');
    $router->get('dispenses', 'Api\DispenseController@index');
});
