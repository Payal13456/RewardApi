<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CountryCode;

class CommonController extends Controller
{
	/**
     * @OA\Post(
     *      path="/api/country-code",
     *      operationId="countryCode",
     *      tags={"Authentication"},
     *      summary="Country code list",
     *      description="Country code list",
     *      @OA\Response(
     *          response=200,
     *          description="Country code List",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      	)
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
   public function countryCode(Request $request){
		$list = CountryCode::select('phone_code','country_code','country_name')->orderBy('phone_code','ASC')->groupBy('phone_code')->get();
		foreach ($list as $key => $value) {
			$value->flag = "https://countryflagsapi.com/png/".$value->country_code;
		}
		return response()->json(['success' => 1, "message" => 'Country Code List' , "data" =>$list])->setStatusCode(200);
	}
}
