<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CountryCode;

class CommonController extends Controller
{
   public function countryCode(Request $request){
		$list = CountryCode::select('phone_code','country_code','country_name')->orderBy('phone_code','ASC')->groupBy('phone_code')->get();
		foreach ($list as $key => $value) {
			$value->flag = "https://countryflagsapi.com/png/".$value->country_code;
		}
		return response()->json(['success' => 1, "message" => 'Country Code List' , "data" =>$list])->setStatusCode(200);
	}
}
