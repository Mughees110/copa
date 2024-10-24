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
Route::post('login2','App\Http\Controllers\AuthController@login2');
Route::post('employ-login','App\Http\Controllers\AuthController@employLogin');
Route::post('user-exists','App\Http\Controllers\AuthController@userExists');

Route::get('invalid',function(){
	 return response()->json(['message'=>'Access token not matched'],422);
})->name('invalid');
use App\Http\Controllers\ClubController;

Route::post('clubs','App\Http\Controllers\ClubController@index');
Route::post('clubs-all','App\Http\Controllers\ClubController@index2');
Route::post('clubs-all-without-pagination','App\Http\Controllers\ClubController@index3');
Route::post('clubs-store','App\Http\Controllers\ClubController@store');
Route::post('clubs-update/{id}','App\Http\Controllers\ClubController@update');
Route::post('clubs-delete/{id}','App\Http\Controllers\ClubController@destroy');

Route::middleware('auth:sanctum')->group(function () {
	Route::post('update','App\Http\Controllers\AuthController@update');
	Route::get('items/{id}','App\Http\Controllers\ItemController@index');
	Route::post('items-store','App\Http\Controllers\ItemController@store');
	Route::post('items-update/{id}','App\Http\Controllers\ItemController@update');
	Route::post('items-delete/{id}','App\Http\Controllers\ItemController@destroy');

	Route::post('stories','App\Http\Controllers\StoryController@index');
	Route::post('stories-panel','App\Http\Controllers\StoryController@index2');
	Route::post('stories-store','App\Http\Controllers\StoryController@store');
	Route::post('stories-update/{id}','App\Http\Controllers\StoryController@update');
	Route::post('stories-delete/{id}','App\Http\Controllers\StoryController@destroy');
	
	Route::post('club-users','App\Http\Controllers\ClubController@getUsers');
	Route::post('favourite','App\Http\Controllers\ClubController@favourite');
	Route::post('get-favourites','App\Http\Controllers\ClubController@getFavourites');

	Route::post('levels','App\Http\Controllers\LevelController@index');
	Route::post('levels-store','App\Http\Controllers\LevelController@store');
	Route::post('levels-update/{id}','App\Http\Controllers\LevelController@update');
	Route::post('levels-delete/{id}','App\Http\Controllers\LevelController@destroy');

	Route::post('regulators','App\Http\Controllers\RegulatorController@index');
	Route::post('regulators-store','App\Http\Controllers\RegulatorController@store');
	Route::post('regulators-update/{id}','App\Http\Controllers\RegulatorController@update');
	Route::post('regulators-delete/{id}','App\Http\Controllers\RegulatorController@destroy');

	Route::post('increment-coins','App\Http\Controllers\AuthController@incrementCoins');
	Route::post('decrement-coins','App\Http\Controllers\AuthController@decrementCoins');

	Route::post('banners','App\Http\Controllers\BannerController@index');
	Route::post('banners-store','App\Http\Controllers\BannerController@store');
	Route::post('banners-update/{id}','App\Http\Controllers\BannerController@update');
	Route::post('banners-delete/{id}','App\Http\Controllers\BannerController@destroy');

	Route::post('seasons','App\Http\Controllers\SeasonController@index');
	Route::post('seasons-store','App\Http\Controllers\SeasonController@store');
	Route::post('seasons-update/{id}','App\Http\Controllers\SeasonController@update');
	Route::post('seasons-delete/{id}','App\Http\Controllers\SeasonController@destroy');

	Route::post('get-user-coins','App\Http\Controllers\AuthController@getUserCoins');
	Route::post('get-user-spins','App\Http\Controllers\AuthController@getUserSpins');

	Route::post('club-with-levels','App\Http\Controllers\ClubController@clubWithLevels');

	Route::post('get-all-users','App\Http\Controllers\AuthController@getAllUsers');
	Route::post('store-exp-points','App\Http\Controllers\PointController@store');
	Route::post('overall-sum','App\Http\Controllers\PointController@overallSum');
	Route::post('points-against-cp','App\Http\Controllers\PointController@pointsAgainstCp');
	Route::post('get-top-users','App\Http\Controllers\PointController@getTopUsers');
	Route::post('clubs-search','App\Http\Controllers\ClubController@clubsSearch');

	Route::post('store-card-info','App\Http\Controllers\AuthController@storeCardInfo');
	Route::post('charge-customer','App\Http\Controllers\AuthController@chargeCustomer');

	Route::post('add-to-calender','App\Http\Controllers\ClubController@addToCalender');
	Route::post('edit-in-calender','App\Http\Controllers\ClubController@editInCalender');
	Route::post('get-calender','App\Http\Controllers\ClubController@getCalender');

	Route::post('upload-file','App\Http\Controllers\ClubController@uploadFile');
	Route::post('upload-files','App\Http\Controllers\ClubController@uploadFiles');

	Route::post('add-to-cart','App\Http\Controllers\CartController@addToCart');
	Route::post('get-my-cart','App\Http\Controllers\CartController@getMyCart');
	Route::post('edit-cart','App\Http\Controllers\CartController@editCart');
	Route::post('delete-cart','App\Http\Controllers\CartController@deleteCart');
});