<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\HelpController;

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
	Route::post('send-otp',[AuthController::class , 'sendOtp']);
	Route::post('/category-list' , [CategoryController::class , 'categoryList']);
	
	Route::post('/vendor-list',[VendorController::class , 'vendorList']);

	Route::middleware('auth:api')->group(function () {
		Route::get('/vendor-details/{id}', [VendorController::class , 'vendirDetails']);
		Route::get('/help/{category_id}', [HelpController::class , 'helpList']);
		Route::get('/help-category', [HelpController::class , 'helpCategory']);
		Route::get('/subscription-history', [PaymentController::class , 'subscriptionHistory']);
		Route::post('/update-basic-info' , [UserController::class , 'updateBasicInfo']);
		Route::post('/update-profile' ,[UserController::class , 'updateProfile']);
		Route::post('/list-plans' ,[UserController::class , 'listPlans']);
		Route::post('/create-customer' ,[PaymentController::class , 'createCustomer']);
		Route::post('/subscribe' ,[PaymentController::class , 'subscribe']);
		Route::post('/bank-list' ,[BankController::class , 'bankList']);
		Route::post('/add-bank' ,[BankController::class , 'addBank']);

		Route::post('support', [UserController::class, 'addSupport']);
	});
});
