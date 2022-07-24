<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserInformation;
use App\Models\Plans;
use App\Models\Feedback;
use App\Models\ReferalBonus;
use App\Models\Redemption;

class UserController extends Controller
{
	 /**
     * @OA\Post(
     *      path="/api/update-profile",
     *      operationId="updateProfile",
     *      tags={"Authorized Users"},
     *      security={{"bearer_token":{}}},
     *      summary="Update Profile",
     *      description="Update Profile after register",
	 * 		@OA\RequestBody(
	 *    required=true,
	 *    description="Pass user credentials",
	 *      @OA\JsonContent(
	 *        required={"emirates_id","passport_no","passport_expiry","address_line1","address_line2","landmark","pincode","country","state","city"},
	 *        @OA\Property(property="emirates_id", type="string", example="TYYTU987979"),
	 *        @OA\Property(property="passport_no", type="string", example="678678786786"),
	 *        @OA\Property(property="passport_expiry", type="date", example="2024-12-12"),
	 *        @OA\Property(property="address_line1", type="string", example="Sayaji Hotel"),
	 *        @OA\Property(property="address_line2", type="string", example="Vijay Nagar"),
	 *        @OA\Property(property="landmark", type="string", example="Near C21 Mall"),
	 *        @OA\Property(property="pincode", type="string", example="452011"),
	 *        @OA\Property(property="country", type="string", example="India"),
	 *        @OA\Property(property="state", type="string", example="MP"),
	 *        @OA\Property(property="city", type="string", example="Indore"),
	 *        ),
   	 *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Profile updated successfully",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
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

          if ($request->profile_image) {
            $folderPath = "/uploads/";
            $image_parts = explode(";base64,", $request->profile_image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $imageName = uniqid() . '.'.$image_type;
            $file = $folderPath . $imageName;
            file_put_contents(public_path().$file, $image_base64);
            $filename = url('/')."/uploads/".$imageName;
            // echo $filename;die;
            $data['profile_image'] = $filename;
            User::where('id' , $request->user()->id)->update(['profile_image'=>$filename]);
          }

      		UserInformation::updateOrCreate(['user_id' => $request->user()->id],$info);
      		
      		$user = User::with('information')->where('id' , $request->user()->id)->first();

      		return response()->json(['success' => 1, "message" => 'Profile Updated Successfully!!' , "data" =>$user]	)->setStatusCode(200);
      	} else{
      		return response()->json(['success' => 0, "message" => 'Something went wrong!!' , "data" =>[]])->setStatusCode(200);
      	}
    }

     /**
     * @OA\Post(
     *      path="/api/list-plans",
     *      operationId="plansList",
     *      tags={"Authorized Users"},
     *      security={{"bearer_token":{}}},
     *      summary="Pricing Plans list",
     *      description="Pricing plans list",
     *      @OA\Response(
     *          response=200,
     *          description="Pricing plans",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */

    public function listPlans(Request $request){
    	$list = Plans::where('status',1)->get();
    	return response()->json(['success' => 1, "message" => 'Pricing Plans' , "data" =>$list]	)->setStatusCode(200);
    }

    public function updateBasicInfo(Request $request){
      $validated = $request->validate([
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email',
        'mobile_no' => 'required',
        'country_code' => 'required',
        'gender' => 'required',
        'dob' => 'required'
      ]);

      if($validated){
        $data = [
          'name' => ucfirst($request->first_name),
          'last_name' => ucfirst($request->last_name),
          'dob' => date('Y-m-d', strtotime($request->dob)),
          'email' => ucfirst($request->email),
          'mobile_no' => $request->mobile_no,
          'country_code' => $request->country_code,
          'gender' => ucfirst($request->gender)
        ];
         User::where('id' , $request->user()->id)->update($data);

         if ($request->profile_image) {
            $folderPath = "/uploads/";
            $image_parts = explode(";base64,", $request->profile_image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $imageName = uniqid() . '.'.$image_type;
            $file = $folderPath . $imageName;
            file_put_contents(public_path().$file, $image_base64);
            $filename = url('/')."/uploads/".$imageName;
            // echo $filename;die;
            $data['profile_image'] = $filename;
            User::where('id' , $request->user()->id)->update(['profile_image'=>$filename]);
          }
        $user =User::where('id' , $request->user()->id)->first();
        return response()->json(['success' => 1, "message" => 'Profile Updated Successfully!!' , "data" =>$user]  )->setStatusCode(200);
      } else{
        return response()->json(['success' => 0, "message" => 'Something went wrong!!' , "data" =>[]])->setStatusCode(200);
      }
    }

    public function addSupport(Request $request){
      $validated = $request->validate([
        'title' => 'required',
        'description' => 'required'
      ]);

      // echo $request->user()->id;die;

      if($validated){
        $data = [
          "title" => $request->title,
          "description" => $request->description,
          "user_id" => $request->user()->id
        ];

        Feedback::create($data);

        return response()->json(['success' => 1, "message" => 'Thank You for reaching us , Our Support team will contact you soon.' , "data" =>[]]  )->setStatusCode(200);
      }else{
        return response()->json(['success' => 0, "message" => 'Something went wrong!!' , "data" =>[]])->setStatusCode(200);
      }
    }

    public function userinfo(Request $request){
      $id = $request->user()->id;

      $user = User::with('subscription','subscription.plan')->where('id',$id)->first();

      $referal = ReferalBonus::where('ref_user_id',$id)->where('status',1 )->sum('amount');

      $redeem = Redemption::where('user_id' , $id)->where('is_approved' , '!=' , 2)->sum('amount');

      $user->redeem_amount = $referal - $redeem;
      $user->courency = 'AED';

      return response()->json(['success' => 1, "message" => 'User Details' , "data" =>$user])->setStatusCode(200);
    }
}


