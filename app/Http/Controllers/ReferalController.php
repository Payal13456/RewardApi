<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReferalBonus;
use App\Models\Redemption;

class ReferalController extends Controller
{
    public function referalHistory(Request $request){
    	$list = ReferalBonus::with('user:id,name,last_name,email,country_code,mobile_no,profile_image')->where('ref_user_id',$request->user()->id)->orderBy('id','DESC')->get();

    	foreach ($list as $key => $value) {
    		if($value->status == 0){
    			$value->referedStatus = 'Registered';
    		} else{
    			$value->referedStatus = 'Activated';
    		}
    	}

    	return response()->json(['success' => 1, "message" => 'Referal History' , "data" => $list])->setStatusCode(200);
    }

    public function redeemHistory(Request $request){
    	$list = Redemption::where('user_id', $request->user()->id)->orderBy('id','desc')->get();

    	if(count($list) > 0){
	    	foreach ($list as $key => $value) {
	    		if($value->is_approved == 0){
	    			$value->redeem_status = "Pending";
	    		} elseif($value->is_approved == 1){
	    			$value->redeem_status = "Accepted";
	    		} else{
	    			$value->redeem_status = "Declined";
	    		}
	    	}
	    	return response()->json(['success' => 1, "message" => 'Redeem History' , "data" => $list])->setStatusCode(200);
	    } else{
			return response()->json(['success' => 1, "message" => 'Empty Redeem History' , "data" => $list])->setStatusCode(200);
	    }

    	
    }

    public function redeemRequest(Request $request){
    	$id = $request->user()->id;
    	$validated = $request->validate([
          "amount" => "required",
          "bank_id" => "required"
      	]);

      	$referal = ReferalBonus::where('ref_user_id',$id)->where('status',1 )->sum('amount');
      	$redeem = Redemption::where('user_id' , $id)->where('is_approved' , '!=' , 2)->sum('amount');
      	$total = $referal - $redeem;

      	if($validated){
      		if($total >= $request->amount && $request->amount > 1){
      			$data = [
      				"amount" => $request->amount,
      				"user_id" => $request->user()->id,
      				"bank_id" => $request->bank_id,
      				"is_approved" => 0,
      				"status" => 0
      			];

      			$req = Redemption::create($data);

      			$req->redeem_status = "Pending";

      			return response()->json(['success' => 1, "message" => 'Redeem request sent to admin for approval, You will get the amount in selected bank when it will be approved by admin' , "data" =>$req])->setStatusCode(200);
      		} else{
      			return response()->json(['success' => 0, "message" => "You don't have sufficient amount in your wallet!!" , "data" => []])->setStatusCode(200);
      		}
      	}
    }

    public function cancelRequest(Request $request){
    	$id = $request->user()->id;
    	$validated = $request->validate([
          "redeem_id" => "required"
      	]);

    	$req = Redemption::where('id',$request->redeem_id)->where('is_approved',0)->first();
    	if($req){	
    		$req->delete();

    		return response()->json(['success' => 1, "message" => 'Redeem request deleted successfully!!' , "data" =>[]])->setStatusCode(200);
    	} else{
    		return response()->json(['success' => 0, "message" => "Redeem request not found or already approved!!" , "data" => []])->setStatusCode(200);
    	}
    }
}
