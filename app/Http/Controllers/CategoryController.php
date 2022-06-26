<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;

class CategoryController extends Controller
{
    /**
	    * @OA\Post(
	    * path="/api/category-list",
	    * operationId="category-list",
	    * tags={"Category list"},
	    * summary="Category List",
	    * description="Category List",
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
	    *          description="Categories",
	    *          @OA\JsonContent()
	    *       ),
	    *      @OA\Response(
	    *          response=200,
	    *          description="Categories List",
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

	public function categoryList(Request $request){
		$list = Categories::all();
		foreach ($list as $key => $value) {
			$value->image = env('WEB_URL' , 'http://localhost:8001/').'uploads/category/'.$value->image;
		}

		return response()->json(['success' => 1, "message" => 'Categories List' , "data" =>$list])->setStatusCode(200);
	}
}
