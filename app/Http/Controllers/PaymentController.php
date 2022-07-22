<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Error\Card;
use Stripe\Stripe;
use Stripe\Charge;
use App\Models\User;
use App\Models\Subscription;
use App\Models\ReferalBonus;

class PaymentController extends Controller
{
    public function createCustomer(Request $request){
    	if(!empty($request->user()->id)){
    		$user = User::find($request->user()->id);
    		if($user->customer_id != null){
	    		$stripe = new \Stripe\StripeClient(
				  env('STRIPE_SECRET_KEY')
				);
				$customer = $stripe->customers->create([
				  'name' => $user->name." ".$user->last_name,
				  'email' => $user->email,
				  'phone' => $user->mobile_no,
				  'description' => 'Sectra App User',
				]);
				$user->customer_id = $customer->id;
				$user->save();

				return response()->json(['success' => 1, "message" => 'Stripe Customer Created Successfully!!' , "data" =>$user])->setStatusCode(200);
			} else{
				return response()->json(['success' => 0, "message" => 'Already having customer id!!' , "data" =>$user])->setStatusCode(200);
			}
    	}
    }

    public function subscribe(Request $request){
    	if(!empty($request->user()->id)){
    		$user = User::find($request->user()->id);
    		// if($user->customer_id != null){
			$stripe = new \Stripe\StripeClient(
			  env('STRIPE_SECRET_KEY')
			);

			$token = $stripe->tokens->create([
			  'card' => [
			    'number' => '4242424242424242',
			    'exp_month' => 7,
			    'exp_year' => 2023,
			    'cvc' => '314',
			  ],
			]);

			$card_token = $token->id;

			// Create customer
			$customer = $stripe->customers->create([
			  'name' => $user->name." ".$user->last_name,
			  'email' => $user->email,
			  'phone' => $user->mobile_no,
			  'description' => 'Sectra App User',
			]);
			
			// Make payment

			$subscribe = $stripe->charges->create([
                "amount" => intval($request->amount) * 100,
                "currency" => "AED",
                "source" => $card_token,
                "description" => "Sectra Plan"
	        ]);
			$unique_card = substr(number_format(time() * mt_rand(),0,'',''),0,16);
			$user->customer_id = $customer->id;
	        $user->subscription_id = $subscribe->id;
	        $user->unique_card = $unique_card;
	        $user->save();

	        $data = [
	        	"user_id" => $request->user()->id,
	        	"plan_id" => $request->plan_id,
	        	"transaction_id" => "TR".substr(number_format(time() * mt_rand(),0,'',''),0,8),
	        	"expiry_date" => date('Y-m-d', strtotime("+30 days")),
	        	"is_expired" => 0,
	        	"status" => 1,
	        	"created_at" => date("Y-m-d H:i:s"),
	        	"updated_at" => date("Y-m-d H:i:s")
	        ];

	        Subscription::create($data);

	        ReferalBonus::where('user_id',$request->user()->id)->update(['status' => 1]);

	        $userinfo = User::with('subscription','subscription.plan')->where('id',$user->id)->first();

	        return response()->json(['success' => 1, "message" => 'Subscribed Successfully!!' , "data" =>$userinfo])->setStatusCode(200);
    		// }
    	}
    }

    public function subscriptionHistory(Request $request){
    	$userid = $request->user()->id;

    	$subscription = Subscription::with('plan')->where('user_id' , $userid)->get();

    	return response()->json(['success' => 1, "message" => 'Subscribed History!!' , "data" =>$subscription])->setStatusCode(200);
    }
}
