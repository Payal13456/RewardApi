<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserInformation;
use App\Models\Plans;

class UserController extends Controller
{
    public function updateProfile(Request $request){
    	$validated = $request->validate([
          'emirates_id' => 'required',
          'passport_no' => 'required',
          'passport_expiry' => 'required',
          'address_line1' => 'required',
          'address_line2' => 'required',
          'landmark' => 'required',
          'pincode' => 'required',
          'country' => 'required',
          'state' => 'required',
          'city' => 'required'
      	]);

      	if($validated){
      		$data = [
      			'emirates_id' =>$request->emirates_id,
      			'passport_no' => $request->passport_no,
      			'passport_expiry' => $request->passport_expiry,
      			'address' => ucfirst($request->address_line1),
      			'status' => 1 ,
      			'role' => 2,
      			'otp_status' => 1
      		];

      		$info = [
      			'address_line1' => ucfirst($request->address_line1),
      			'address_line2' => ucfirst($request->address_line2),
      			'pincode' => $request->pincode,
      			'state' => ucfirst($request->state),
      			'landmark' =>ucfirst($request->landmark),
      			'user_id' => $request->user()->id,
      			'city' => ucfirst($request->city),
      			'country' => ucfirst($request->country)
      		];

      		User::where('id' , $request->user()->id)->update($data);

      		UserInformation::updateOrCreate(['user_id' => $request->user()->id],$info);
      		
      		$user = User::with('information')->where('id' , $request->user()->id)->first();

      		return response()->json(['success' => 1, "message" => 'Profile Updated Successfully!!' , "data" =>$user]	)->setStatusCode(200);
      	} else{
      		return response()->json(['success' => 0, "message" => 'Something went wrong!!' , "data" =>[]])->setStatusCode(200);
      	}
    }

    public function listPlans(Request $request){
    	$list = Plans::where('status',1)->get();
    	return response()->json(['success' => 1, "message" => 'Pricing Plans' , "data" =>$list]	)->setStatusCode(200);
    }
}
