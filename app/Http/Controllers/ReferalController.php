<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReferalBonus;

class ReferalController extends Controller
{
    public function referalHistory(Request $request){
    	$list = ReferalBonus::with('user:id,name,last_name,email,country_code,mobile_no')->where('ref_user_id',$request->user()->id)->orderBy('id','DESC')->get();

    	foreach ($list as $key => $value) {
    		if($value->status == 0){
    			$value->referedStatus = 'Registered';
    		} else{
    			$value->referedStatus = 'Activated';
    		}
    	}

    	return response()->json(['success' => 1, "message" => 'Referal History' , "data" =>$list])->setStatusCode(200);
    }
}
