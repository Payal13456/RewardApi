<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CountryCode;

class CommonController extends Controller
{
    /**
	    * @OA\Post(
	    * path="/api/country-code",
	    * operationId="country-code",
	    * tags={"Country Code list"},
	    * summary="Country Code List",
	    * description="Country Code List",
	    *     @OA\RequestBody(
	    *         @OA\JsonContent(),
	    *         @OA\MediaType(
	    *            mediaType="multipart/form-data",
	    *            @OA\Schema(
	    *               type="object"
	    *            ),
	    *        ),
	    *    ),
	    *      @OA\Response(
	    *          response=201,
	    *          description="Country Codes",
	    *          @OA\JsonContent()
	    *       ),
	    *      @OA\Response(
	    *          response=200,
	    *          description="Country Code List",
	    *          @OA\JsonContent()
	    *       ),
	    *      @OA\Response(
	    *          response=422,
	    *          description="Validation Failed",
	    *          @OA\JsonContent()
	    *       ),
	    *      @OA\Response(response=400, description="Bad request"),
	    *      @OA\Response(response=404, description="Resource Not Found")
	    * )
	*/

	public function countryCode(Request $request){
		$list = CountryCode::select('phone_code','country_code','country_name')->orderBy('phone_code','ASC')->groupBy('phone_code')->get();
		foreach ($list as $key => $value) {
			$value->flag = "https://countryflagsapi.com/png/".$value->country_code;
		}
		return response()->json(['success' => 1, "message" => 'Country Code List' , "data" =>$list])->setStatusCode(200);
	}
}
