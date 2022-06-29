<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
    * @OA\Post(
    * path="/api/register",
    * operationId="Register",
    * tags={"Register"},
    * summary="User Register",
    * description="User Register here",
    *     @OA\RequestBody(
    *         @OA\JsonContent(),
    *         @OA\MediaType(
    *            mediaType="multipart/form-data",
    *            @OA\Schema(
    *               type="object",
    *               required={"first_name","email",  "last_name","country_code","mobile_no",'gender','dob'},
    *               @OA\Property(property="first_name", type="text"),
    *				@OA\Property(property="last_name", type="text"),
    *				@OA\Property(property="country_code", type="text"),
    *				@OA\Property(property="mobile_no", type="text"),
    *               @OA\Property(property="email", type="text"),
    *               @OA\Property(property="gender", type="text"),
    *               @OA\Property(property="dob", type="date")
    *            ),
    *        ),
    *    ),
    *      @OA\Response(
    *          response=201,
    *          description="Register Successfully",
    *          @OA\JsonContent()
    *       ),
    *      @OA\Response(
    *          response=200,
    *          description="Register Successfully",
    *          @OA\JsonContent()
    *       ),
    *      @OA\Response(
    *          response=422,
    *          description="Unprocessable Entity",
    *          @OA\JsonContent()
    *       ),
    *      @OA\Response(response=400, description="Bad request"),
    *      @OA\Response(response=404, description="Resource Not Found"),
    * )
    */
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
      	'name' => $request->first_name,
      	'last_name' => $request->last_name,
      	'dob' => $request->dob,
      	'email' => $request->email,
      	'mobile_no' => $request->mobile_no,
      	'country_code' => $request->country_code,
      	'gender' => $request->gender
      ];
      $data['password'] = Hash::make($data['email']);
      $data['unique_card'] = substr(number_format(time() * mt_rand(),0,'',''),0,16);
      $user = User::create($data);
      $success['token'] =  $user->createToken('authToken')->accessToken;
      return response()->json(['success' => 1, "message" => 'Register Successfully' , "data" =>$success])->setStatusCode(200);
  	}

  	/**
    * @OA\Post(
    * path="/api/login",
    * operationId="authLogin",
    * tags={"Login"},
    * summary="User Login",
    * description="Login User Here",
    *     @OA\RequestBody(
    *         @OA\JsonContent(),
    *         @OA\MediaType(
    *            mediaType="multipart/form-data",
    *            @OA\Schema(
    *               type="object",
    *               required={"email", "password"},
    *               @OA\Property(property="email", type="email"),
    *               @OA\Property(property="password", type="password")
    *            ),
    *        ),
    *    ),
    *      @OA\Response(
    *          response=201,
    *          description="Login Successfully",
    *          @OA\JsonContent()
    *       ),
    *      @OA\Response(
    *          response=200,
    *          description="Login Successfully",
    *          @OA\JsonContent()
    *       ),
    *      @OA\Response(
    *          response=422,
    *          description="Unprocessable Entity",
    *          @OA\JsonContent()
    *       ),
    *      @OA\Response(response=400, description="Bad request"),
    *      @OA\Response(response=404, description="Resource Not Found"),
    * )
    */
  	public function login(Request $request)
  	{
      $validator = $request->validate([
          'email' => 'email|required',
          'password' => 'required'
      ]);

      if (!auth()->attempt($validator)) {
        return response()->json(['success' => 0, "message" => 'Unauthorised' , "data" =>[]])->setStatusCode(401);
      } else {
          $success['token'] = auth()->user()->createToken('authToken')->accessToken;
          $success['user'] = auth()->user();
          return response()->json(['success' => 1, "message" => 'Login Successfully' , "data" =>$success])->setStatusCode(200);
      }
  	}

  	/**
    * @OA\Post(
    * path="/api/loginByMobileNumber",
    * operationId="loginByMobileNumber",
    * tags={"Login By Mobile Number"},
    * summary="Login By Mobile Number",
    * description="Login User Here by mobile number",
    *     @OA\RequestBody(
    *         @OA\JsonContent(),
    *         @OA\MediaType(
    *            mediaType="multipart/form-data",
    *            @OA\Schema(
    *               type="object",
    *               required={"mobile_no", "country_code"},
    *               @OA\Property(property="mobile_no", type="text"),
    *               @OA\Property(property="country_code", type="text")
    *            ),
    *        ),
    *    ),
    *      @OA\Response(
    *          response=201,
    *          description="Login Successfully",
    *          @OA\JsonContent()
    *       ),
    *      @OA\Response(
    *          response=200,
    *          description="Login Successfully",
    *          @OA\JsonContent()
    *       ),
    *      @OA\Response(
    *          response=422,
    *          description="Unprocessable Entity",
    *          @OA\JsonContent()
    *       ),
    *      @OA\Response(response=400, description="Bad request"),
    *      @OA\Response(response=404, description="Resource Not Found"),
    * )
    */
  	public function loginByMobileNumber(Request $request)
  	{
      $validator = $request->validate([
      	  'country_code' => 'required',
          'mobile_no' => 'required'
      ]);

      if (!auth()->attempt($		)) {
          return response()->json(['success' => 0, "message" => 'Unauthorised' , "data" =>[]])->setStatusCode(401);
      } else {
          $success['token'] = auth()->user()->createToken('authToken')->accessToken;
          $success['user'] = auth()->user();
          return response()->json(['success' => 1, "message" => 'Login Successfully' , "data" =>$success])->setStatusCode(200);
      }
  	}

  	/**
	    * @OA\Post(
	    * path="/api/checkUser",
	    * operationId="checkUser",
	    * tags={"Check Mobile Number exist or not before registration"},
	    * summary="Check User",
	    * description="Check Mobile No Before registration",
	    *     @OA\RequestBody(
	    *         @OA\JsonContent(),
	    *         @OA\MediaType(
	    *            mediaType="multipart/form-data",
	    *            @OA\Schema(
	    *               type="object",
	    *               required={"mobile_no"},
	    *               @OA\Property(property="mobile_no", type="text")
	    *            ),
	    *        ),
	    *    ),
	    *      @OA\Response(
	    *          response=201,
	    *          description="User Not Found",
	    *          @OA\JsonContent()
	    *       ),
	    *      @OA\Response(
	    *          response=200,
	    *          description="User Exist , Please login",
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
    public function checkUser(Request $request){
    	$validator = $request->validate([
	        'mobile_no' => 'required',
	    ]);

    	abort_if(!$validator , 422);

	 	$user = User::where('mobile_no', $request->mobile_no)->first();
	 		
	 	if(empty($user)){
	 		return response()->json(['success' => 1, "message" => 'User Not found' , "data" =>[]])->setStatusCode(201);
	 	}

	 	return response()->json(['success' => 0, "message" => 'User Exist , Please login' , "data" =>$user])->setStatusCode(200);

    }
}
