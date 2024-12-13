<?php

return [
    // Pages publiques
    'GET /' => 'EggController@index',
    'GET /api/eggs' => 'EggController@getAllEggs',
    'GET /api/eggs/{name}' => 'EggController@getEggByName',
    
    // Administration
    'GET /admin' => 'DashboardController@index',
    'POST /admin/eggs' => 'AdminController@createEgg',
    'PUT /admin/eggs/{name}' => 'AdminController@updateEgg',
    'DELETE /admin/eggs/{name}' => 'AdminController@deleteEgg'
];
