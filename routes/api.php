<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategorieController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix("categories")->middleware('auth:sanctum')->group(function(){

    Route::get('/',[CategorieController::class,'index']);
    Route::get('/{id}',[CategorieController::class,'show']);
    Route::post('/',[CategorieController::class,'store']);
    Route::put('/{id}',[CategorieController::class,'update']);
    Route::delete('/{id}',[CategorieController::class,'destroy']);
});


//Route::get('products/{name}',[ProductController::class,'search']);
Route::prefix("products")->middleware('auth:sanctum')->group(function(){

    Route::get('/',[ProductController::class,'index']);
    Route::get('/search/{name}',[ProductController::class,'search']);
    Route::get('/{id}',[ProductController::class,'show']);
    Route::post('/',[ProductController::class,'store']);
    Route::put('/{id}',[ProductController::class,'update']);
    Route::delete('/{id}',[ProductController::class,'destroy']);
});



// Route::resource('categories',CategorieController::class,['middleware'=>['auth:sanctum']]);
// Route::resource('products',ProductController::class,['middleware'=>['auth:sanctum']]);
// Route::get('products/search/{name}',['middleware'=>['auth:sanctum'],ProductController::class,'search',]);


//Auth

// Route::post('/register',[AuthController::class,'register']);
Route::prefix("user")->group(function(){    
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login']);
    Route::middleware('auth:sanctum')->get('/logout',[AuthController::class,'logout']);

});


