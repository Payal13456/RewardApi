<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankDetails;

class BankController extends Controller
{
    public function bankList(Request $request){
    	if($request->user()->id){
    		$list = BankDetails::where('user_id',$request->user()->id)->get();
 			return response()->json(['success' => 1, "message" => 'Bank list' , "data" =>$list])->setStatusCode(200);
    	} else{
    		 return response()->json(['success' => 0, "message" => 'Invalid user id' , "data" =>[]])->setStatusCode(200);
    	}
    }	

    public function addBank(Request $request){
    	$validated = $request->validate([
          "bank_name" => "required","branch"=> "required","acc_no"=> "required","ifsc_code"=> "required","beneficiary_name"=> "required","iban_no"=> "required"
      	]);

      	if($validated){
      		$bankinfo = [
	          "bank_name" => ucfirst($request->bank_name),
	          "branch"=> $request->branch,
	          "acc_no"=> $request->acc_no,
	          "ifsc_code"=> $request->ifsc_code,
	          "beneficiary_name"=> $request->beneficiary_name,
	          "iban_no"=> $request->iban_no,
	          "user_id" => $request->user()->id
	      	];
      		$data = BankDetails::create($bankinfo);
      		return response()->json(['success' => 1, "message" => 'Bank Details added successfully!!' , "data" =>$data])->setStatusCode(200);
      	} else{
      		return response()->json(['success' => 0, "message" => 'Something went wrong!!' , "data" =>[]])->setStatusCode(200);
      	}
    }
}
