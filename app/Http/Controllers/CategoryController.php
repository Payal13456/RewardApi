<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;

class CategoryController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/category-list",
     *      operationId="categoryList",
     *      tags={"Category"},
     *      summary="Get list of category",
     *      description="Returns list of category",
     *      @OA\Response(
     *          response=200,
     *          description="Category List",
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
    public function categoryList(Request $request){
		$list = Categories::with('vendor')->get();
		foreach ($list as $key => $value) {
			$value->image = env('WEB_URL' , 'http://localhost:8001/').'uploads/category/'.$value->image;
               foreach($value->vendor as $vendor){
                    $vendor->shop_logo = env('WEB_URL' , 'http://localhost:8001/').'uploads/category/'.$vendor->shop_logo;
               }
		}
		return response()->json(['success' => 1, "message" => 'Categories List' , "data" =>$list])->setStatusCode(200);
	}
}
