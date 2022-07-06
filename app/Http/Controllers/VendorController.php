<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendors;

class VendorController extends Controller
{
	/**
     * @OA\Post(
     *      path="/api/vendor-list",
     *      operationId="vendorList",
     *      tags={"Vendor"},
     *      security={{"bearer_token":{}}},
     *      summary="Get list of vendor",
     *      description="Returns list of vendor",
     *      @OA\Response(
     *          response=200,
     *          description="Vendor List",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
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
    public function vendorList(Request $request){
    	if(isset($request->category_id) && $request->category_id != 0){
    		$list = Vendors::with('category','offers')->where('category_id',$request->category_id)->get();
    	} else{
    		$list = Vendors::with('category','offers')->get();
    	}
		foreach ($list as $key => $value) {
			$value->shop_logo = env('WEB_URL' , 'http://localhost:8001/').'uploads/category/'.$value->shop_logo;
            $value->category->image = env('WEB_URL' , 'http://localhost:8001/').'uploads/category/'.$value->image;
		}
		return response()->json(['success' => 1, "message" => 'Vendors List' , "data" =>$list])->setStatusCode(200);
    }
}
