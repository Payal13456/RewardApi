<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\Models\User;
use Auth;

class AuthController extends Controller
{
  /**
   * @OA\Post(
   * path="/api/register",
   * summary="Register Api",
   * description="App Registration",
   * operationId="authRegister",
   * tags={"Authentication"},
   * @OA\RequestBody(
   *    required=true,
   *    description="Pass user credentials",
   *    @OA\JsonContent(
   *       required={"first_name","last_name","email","mobile_no","country_code","gender","dob"},
   *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
   *       @OA\Property(property="first_name", type="string", example="John"),
   *       @OA\Property(property="last_name", type="string", example="Son"),
   *       @OA\Property(property="mobile_no", type="string", example="1234567890"),
   *       @OA\Property(property="country_code", type="string", example="+1"),
   *       @OA\Property(property="gender", type="string", example="Male"),
   *       @OA\Property(property="dob", type="date", example="2022-01-01"),
   *    ),
   * ),
   * @OA\Response(
   *    response=422,
   *    description="Validation Error",
   *    @OA\JsonContent(
   *       @OA\Property(property="message", type="string", example="Validation Error")
   *        )
   *     )
   * )
    * @OA\Response(
   *    response=200,
   *    description="Register Successfully",
   * )
   */

  public function register(Request $request)
	{
    $validated = $request->validate([
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email|unique:users',
        'mobile_no' => 'required|unique:users',
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
    	'gender' => ucfirst($request->gender),
      'referal_code' => ucfirst( substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstvwxyz0123456789"), 0, 10))
    ];

    $data['password'] = Hash::make($data['email']);
    $data['unique_card'] = substr(number_format(time() * mt_rand(),0,'',''),0,16);
    $user = User::create($data);
    $success['token'] = $user->createToken('Laravel Personal Access Client')->accessToken;
    $success['user'] = $user;
    return response()->json(['success' => 1, "message" => 'Register Successfully' , "data" =>$success])->setStatusCode(200);
	}


	public function login(Request $request)
	{
    $validator = $request->validate([
        'email' => 'email|required',
        'otp' => 'required'
    ]);

    $user = User::where('email', $request->get('email'))->first();

    if(!empty($user)){
      if(!Auth::loginUsingId($user->id)){
         return response()->json(['success' => 0, "message" => 'User credentials doesn\'t match.' , "data" =>[]])->setStatusCode(401);
      } else {
        $success['token'] = $user->createToken('Laravel Personal Access Client')->accessToken;

        $success['user'] = $user;
        return response()->json(['success' => 1, "message" => 'Login Successfully!!' , "data" =>$success])->setStatusCode(200);
      }
    }else{
      return response()->json(['success' => 0, "message" => 'User Not found!!' , "data" =>[]])->setStatusCode(401);
    }
	}

	/**
   * @OA\Post(
   * path="/api/loginByMobileNumber",
   * summary="Login By MobileNumber",
   * description="Login by mobile number",
   * operationId="authLogin",
   * tags={"Authentication"},
   * @OA\RequestBody(
   *    required=true,
   *    description="Pass user credentials",
   *    @OA\JsonContent(
   *       required={"country_code","mobile_no"},
   *       @OA\Property(property="country_code", type="string", example="+91"),
   *       @OA\Property(property="mobile_no", type="string", example="8435153945"),
   *    ),
   * ),
   * @OA\Response(
   *    response=422,
   *    description="Validation Error",
   *    @OA\JsonContent(
   *       @OA\Property(property="message", type="string", example="Validation Error")
   *        )
   *     )
   * )
   */
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
        $success['token'] = $user->createToken('Laravel Personal Access Client')->accessToken;

        $success['user'] = $user;
        return response()->json(['success' => 1, "message" => 'Login Successfully!!' , "data" =>$success])->setStatusCode(200);
      }
    }else{
      return response()->json(['success' => 0, "message" => 'User Not found!!' , "data" =>[]])->setStatusCode(401);
    }
	}

	/**
   * @OA\Post(
   * path="/api/checkUser",
   * summary="Check User MobileNumber",
   * description="Check User mobile number",
   * operationId="checkUser",
   * tags={"Authentication"},
   * @OA\RequestBody(
   *    required=true,
   *    description="Pass user credentials",
   *    @OA\JsonContent(
   *       required={"country_code","mobile_no"},
   *       @OA\Property(property="country_code", type="string", example="+91"),
   *       @OA\Property(property="mobile_no", type="string", example="8435153945"),
   *    ),
   * ),
   * @OA\Response(
   *    response=422,
   *    description="Validation Error",
   *    @OA\JsonContent(
   *       @OA\Property(property="message", type="string", example="Validation Error")
   *        )
   *     )
   * )
   */
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

  public function sendOtp(Request $request){
    $validator = $request->validate([
      'email' => 'email|required'
    ]);

    // $otp = substr(number_format(time() * mt_rand(),0,'',''),0,6);
    $otp = "1234";

    if(User::where('email',$request->email)->first()){
      User::where('email',$request->email)->update(['otp'=>$otp]);

      // Mail code will come here

      return response()->json(['success' => 1, "message" => 'Otp sent on '.$request->email , "data" =>$otp])->setStatusCode(200);
    } else{
      return response()->json(['success' => 0, "message" => 'User Not found!!' , "data" =>[]])->setStatusCode(401);
    }
  }
}
