<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('categories')->middleware('auth:sanctum')->controller(CategoryController::class)->group(function () {
    Route::get('/', 'index')->withoutMiddleware('auth:sanctum');
    Route::get('/search', 'search')->withoutMiddleware('auth:sanctum');
    Route::get('/{id}', 'show')->withoutMiddleware('auth:sanctum');
    Route::post('/', 'store');
    Route::post('/{id}', 'update');
    Route::delete('/{id}', 'delete');

});
Route::prefix('products')->controller(ProductController::class)->group(function () {
    Route::get('/', 'index')->withoutMiddleware('auth:sanctum');
    Route::get('/search', 'search')->withoutMiddleware('auth:sanctum');
    Route::get('/{id}', 'show')->withoutMiddleware('auth:sanctum');
    Route::post('/', 'store');
    Route::post('/{id}', 'update');
    Route::delete('/{id}', 'delete');
});

//Register
Route::prefix('users')->middleware('auth:sanctum')->controller(AuthController::class)->group(function () {
    Route::post('/register', 'register')->withoutMiddleware('auth:sanctum');
    Route::post('/login', 'login')->withoutMiddleware('auth:sanctum');
    Route::get('/logout', 'logout');
});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Route::prefix('auth')->controller(AuthController::class)->middleware('auth:api')->group(function ($router) {

//     Route::post('login', 'login');
//     Route::post('logout', 'AuthController@logout');
//     Route::post('refresh', 'AuthController@refresh');
//     Route::post('me', 'AuthController@me');

// });