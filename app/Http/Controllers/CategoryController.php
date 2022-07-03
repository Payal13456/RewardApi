<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;

class CategoryController extends Controller
{
    public function categoryList(Request $request){
		$list = Categories::all();
		foreach ($list as $key => $value) {
			$value->image = env('WEB_URL' , 'http://localhost:8001/').'uploads/category/'.$value->image;
		}

		return response()->json(['success' => 1, "message" => 'Categories List' , "data" =>$list])->setStatusCode(200);
	}
}
