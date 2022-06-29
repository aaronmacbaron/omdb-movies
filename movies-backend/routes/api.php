<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SeriesController;

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

Route::resource('movies', MovieController::class);
Route::resource('series', SeriesController::class);

Route::get('/search/movies/{term}', [MovieController::class, 'search']);
Route::get('/search/movies/{term}/{page}', [MovieController::class, 'search']);

Route::get('/search/series/{term}', [MovieController::class, 'search']);
Route::get('/search/series/{term}/{page}', [MovieController::class, 'search']);