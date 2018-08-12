<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/map', 'MapController@index');

Route::post('/getcities', 'MapController@getcities');
Route::post('/getcitiesnameoption', 'MapController@getcitiesNameOption');
/*
Route::get('/', function(){
    $config = array();
    $config['center'] = 'Yerevan Armenia';
    GMaps::initialize($config);
    $map = GMaps::create_map();

    echo $map['js'];
    echo $map['html'];
});*/