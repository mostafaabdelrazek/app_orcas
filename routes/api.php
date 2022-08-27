<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FetchUserDataController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Route::get('/fetch', [FetchUserDataController::class, 'Fetch']);
Route::get(
    '/fetcheduser',
    [FetchUserDataController::class, 'Get']
)->middleware('auth.basic');;
Route::get(
    '/fetcheduser/search/{strSearchText}',
    [FetchUserDataController::class, 'SearchUser']
)->middleware('auth.basic');;
