<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('register','App\Http\Controllers\AuthController@register');
//Route::post('gmail','App\Http\Controllers\AuthController@gmail');
Route::post('login','App\Http\Controllers\AuthController@login');

Route::get('invalid',function(){
	 return response()->json(['message'=>'Access token not matched'],422);
})->name('invalid');
use App\Http\Controllers\ClubController;

Route::apiResource('clubs', ClubController::class);