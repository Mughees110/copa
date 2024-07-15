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

Route::get('clubs','App\Http\Controllers\ClubController@index');
Route::post('clubs-store','App\Http\Controllers\ClubController@store');
Route::post('clubs-update/{id}','App\Http\Controllers\ClubController@update');
Route::post('clubs-delete/{id}','App\Http\Controllers\ClubController@destroy');

Route::middleware('auth:sanctum')->group(function () {
	Route::get('items/{id}','App\Http\Controllers\ItemController@index');
	Route::post('items-store','App\Http\Controllers\ItemController@store');
	Route::post('items-update/{id}','App\Http\Controllers\ItemController@update');
	Route::post('items-delete/{id}','App\Http\Controllers\ItemController@destroy');

	Route::post('stories/{id}','App\Http\Controllers\StoryController@index');
	Route::post('stories-store','App\Http\Controllers\StoryController@store');
	Route::post('stories-update/{id}','App\Http\Controllers\StoryController@update');
	Route::post('stories-delete/{id}','App\Http\Controllers\StoryController@destroy');
	
	Route::post('club-users','App\Http\Controllers\ClubController@getUsers');
	Route::post('favourite','App\Http\Controllers\ClubController@favourite');
	Route::post('get-favourites','App\Http\Controllers\ClubController@getFavourites');
});