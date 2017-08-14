<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/contacts', function () {
    return view('contacts');
});

Route::get('/home', 'HomeController@index');
Route::post('/home', 'HomeController@actionWithSite');

Route::get('/account', 'AccountController@index');
Route::post('/account', 'AccountController@changeUserData');

Route::get('/addSite', 'AddSiteController@index');
Route::post('/addSite', 'AddSiteController@getSiteData');

Route::get('/addPages/{url}', 'AddPagesController@index');
Route::post('/addPages', 'AddPagesController@getUrls');
Route::get('/addPages', function () {
    return view('errors.404');
});

Route::get('/sitePages/{url}', 'SitePagesController@index');
Route::post('/sitePages', 'SitePagesController@reditectToAddPageOrDeletePage');
Route::get('/sitePages', function () {
    return view('errors.404');
});

Route::get('/parameters/{id}', 'ParametersController@index');
Route::post('/parameters', 'ParametersController@goToPages');
