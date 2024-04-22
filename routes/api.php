<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
//bepa 2020-08-20
$proxy_url = getenv('PROXY_URL');
$proxy_schema = getenv('PROXY_SCHEMA');
if (!empty($proxy_url)) {
    URL::forceRootUrl($proxy_url);
}
if (!empty($proxy_schema)) {
    URL::forceScheme($proxy_schema);
}
*/

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// Overall
Route::post('berjaya_group', 'API\APIController@berjaya_group');

// Vertical Level
Route::post('vertical', 'API\APIController@vertical');

// Industry Level
Route::post('industry', 'API\APIController@industry');

// Company Level
Route::post('company', 'API\APIController@company');