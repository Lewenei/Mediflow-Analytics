<?php

$router->get('/', function () {
    return response()->json([
        'project'   => 'MediFlow Analytics',
        'version'   => '1.0.0',
        'message'   => 'Hospital Executive Analytics Platform',
        'status'    => 'active',
        'time'      => now()->toDateTimeString(),
        'author'    => 'Your Name'
    ]);
});