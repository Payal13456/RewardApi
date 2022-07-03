<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\Models\User;
use Auth;

class AuthController extends Controller
{
    public function register(Request $request)
  	{
      $validated = $request->validate([
          'first_name' => 'required',
          'last_name' => 'required',
          'email' => 'required|email|unique:users',
          'mobile_no' => 'required',
          'country_code' => 'required',
          'gender' => 'required',
          'dob' => 'required'
      ]);


      $data = [
      	'name' => ucfirst($request->first_name),
      	'last_name' => ucfirst($request->last_name),
      	'dob' => date('Y-m-d', strtotime($request->dob)),
      	'email' => ucfirst($request->email),
      	'mobile_no' => $request->mobile_no,
      	'country_code' => $request->country_code,
      	'gender' => ucfirst($request->gender)
      ];

      $data['password'] = Hash::make($data['email']);
      $data['unique_card'] = substr(number_format(time() * mt_rand(),0,'',''),0,16);
      $user = User::create($data);
      $token = explode('|',$user->createToken('authToken')->plainTextToken);
      $success['token'] = $token[1];
      $success['user'] = $user;
      return response()->json(['success' => 1, "message" => 'Register Successfully' , "data" =>$success])->setStatusCode(200);
  	}

  	
  	public function login(Request $request)
  	{
      $validator = $request->validate([
          'email' => 'email|required',
          'password' => 'required'
      ]);

      if (!auth()->attempt($validator)) {
        return response()->json(['success' => 0, "message" => 'Unauthorised' , "data" =>[]])->setStatusCode(401);
      } else {
          $token = explode('|', auth()->user()->createToken('authToken')->plainTextToken);
          $success['token'] = $token[1];
          $success['user'] = auth()->user();
          return response()->json(['success' => 1, "message" => 'Login Successfully' , "data" =>$success])->setStatusCode(200);
      }
  	}

  	
  	public function loginByMobileNumber(Request $request)
  	{
      $validator = $request->validate([
      	  'country_code' => 'required',
          'mobile_no' => 'required|regex:/[0-9]{10}/|digits:10'
      ]);

      $user = User::where('mobile_no', $request->get('mobile_no'))->where('country_code',$request->country_code)->first();

      if(!empty($user)){
        if(!Auth::loginUsingId($user->id)){
           return response()->json(['success' => 0, "message" => 'User credentials doesn\'t match.' , "data" =>[]])->setStatusCode(401);
        } else {
          $token = explode('|',$user->createToken('authToken')->plainTextToken);
          $success['token'] = $token[1];
          $success['user'] = $user;
          return response()->json(['success' => 1, "message" => 'Login Successfully!!' , "data" =>$success])->setStatusCode(200);
        }
      }else{
        return response()->json(['success' => 0, "message" => 'User Not found!!' , "data" =>[]])->setStatusCode(401);
      }
  	}

  	
    public function checkUser(Request $request){
    	$validator = $request->validate([
	        'mobile_no' => 'required',
          'country_code' => 'required'
	    ]);

    	abort_if(!$validator , 422);

  	 	$user = User::where('mobile_no', $request->mobile_no)->where('country_code',$request->country_code)->first();
  	 		
  	 	if(empty($user)){
  	 		return response()->json(['success' => 1, "message" => 'User Not found' , "data" =>[]])->setStatusCode(201);
  	 	}

  	 	return response()->json(['success' => 0, "message" => 'User Exist , Please login' , "data" =>$user])->setStatusCode(200);

      }
}
