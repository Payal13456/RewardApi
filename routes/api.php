<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\CategoryController;

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

Route::post('country-code' , [CommonController::class , 'countryCode']);
Route::post('loginByMobileNumber', [AuthController::class , 'loginByMobileNumber']);
Route::post('login', [AuthController::class , 'login']);
Route::post('register', [AuthController::class , 'register']);
Route::post('checkUser',[AuthController::class , 'checkUser']);

Route::post('category-list' , [CategoryController::class , 'categoryList']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});