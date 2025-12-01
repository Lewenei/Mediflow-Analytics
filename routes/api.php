<?php

use Illuminate\Http\Request;

$router->get('/', function () {
    return response()->json([
        'project' => 'MediFlow Analytics',
        'version' => '1.0',
        'message' => 'Hospital Intelligence Platform – Ready',
        'endpoints' => [
            'GET /api/patients',
            'POST /api/patients',
            'GET /api/patients/{id}',
            'GET /api/analytics/revenue-kpi (coming)',
            '/dashboard (live demo)'
        ]
    ]);
});

$router->group(['prefix' => 'patients'], function ($router) {
    $router->get('/', 'Api\PatientController@index');
    $router->post('/', 'Api\PatientController@store');
    $router->get('/{patient}', 'Api\PatientController@show');
});