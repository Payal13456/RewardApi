<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Help;
use App\Models\HelpCategory;

class HelpController extends Controller
{
    public function helpList($category_id){
    	$help = Help::with('category')->where('category',$category_id)->get();
 		return response()->json(['success' => 1, "message" => 'List Help' , "data" =>$help])->setStatusCode(200);
    }

    public function helpCategory(){
    	$category = HelpCategory::all();
 		return response()->json(['success' => 1, "message" => 'List Help category' , "data" =>$category])->setStatusCode(200);
    }
}
