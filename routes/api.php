<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;

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

Route::group(['middleware' => ['cors', 'json.response']], function () {
	Route::post('country-code' , [CommonController::class , 'countryCode']);
	Route::post('loginByMobileNumber', [AuthController::class , 'loginByMobileNumber']);
	Route::post('login', [AuthController::class , 'login']);
	Route::post('register', [AuthController::class , 'register']);
	Route::post('checkUser',[AuthController::class , 'checkUser']);


	Route::middleware('auth:api')->group(function () {
		Route::post('/update-profile' ,[UserController::class , 'updateProfile']);
		Route::post('/list-plans' ,[UserController::class , 'listPlans']);
		Route::post('/category-list' , [CategoryController::class , 'categoryList']);
	});
});
