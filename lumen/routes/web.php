<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */

// $router->get('/', 'MonitorController@index');
$router->get('/', function($router){
    return 'hello world';
});
    
$router->group(['prefix' => 'details'], function () use ($router) {
    $router->get('client', 'DetailsController@client');
    $router->get('maxdate', 'DetailsController@maxdate');
    $router->post('zone', 'DetailsController@zone');
    $router->post('cred', 'DetailsController@user');
    $router->post('reading', 'DetailsController@reading');
    $router->get('/stats/{name}', 'StatController@statView');
});

$router->group(['prefix' => 'insert'], function () use ($router) {
    $router->post('single', 'InsertController@single');
    $router->post('reading', 'InsertController@reading');
    $router->post('json', 'InsertController@json');
    $router->post('excel', 'InsertController@excel');
});

$router->group(['prefix'=> 'map'], function($router){
    $router->get('coordinates', 'MapController@coordinates');
    $router->get('update', 'MapController@onlineCoordinates');
    $router->post('remove', "MapController@remove");
});


$router->group(['prefix'=>'unread'], function($router){
    $router->get('/{year}/{month}', 'UnreadController@getUnreadClients' );
    $router->get('/raw/{year}/{month}', 'UnreadController@sendUnreadClients');
});

$router->group(['prefix'=>'client'], function ($router){
    $router->post('/', 'ClientController@getClientDetails');
});

$router->group(['prefix'=>'auth'], function($router){
   $router->get('/register', 'AuthController@register'); 
   $router->post('/register', 'AuthController@insert'); 
   $router->get('/login', 'AuthController@login');
});

$router->get('/upload', 'FileController@upload');
$router->post('/stat', 'StatController@stats');