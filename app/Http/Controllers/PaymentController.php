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
    		if($user->customer_id == null){
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
    		
			$unique_card = substr(number_format(time() * mt_rand(),0,'',''),0,16);
			$user->customer_id = $customer->id;
	        $user->subscription_id = $subscribe->id;
	        $user->unique_card = $unique_card;
	        $user->save();

	        $plan = Plan::find($request->plan_id);

	        $data = [
	        	"user_id" => $request->user()->id,
	        	"plan_id" => $request->plan_id,
	        	"transaction_id" => "TR".substr(number_format(time() * mt_rand(),0,'',''),0,8),
	        	"expiry_date" => date('Y-m-d', strtotime($plan->validity." days")),
	        	"is_expired" => 0,
	        	"status" => 1,
	        	"created_at" => date("Y-m-d H:i:s"),
	        	"updated_at" => date("Y-m-d H:i:s")
	        ];

	        Subscription::create($data);

	        ReferalBonus::where('user_id',$request->user()->id)->update(['status' => 1]);

	        $userinfo = User::with('subscription','subscription.plan')->where('id',$user->id)->first();

	        return response()->json(['success' => 1, "message" => 'Subscribed Successfully!!' , "data" =>$userinfo])->setStatusCode(200);
    	}
    }

    public function subscriptionHistory(Request $request){
    	$userid = $request->user()->id;

    	$subscription = Subscription::with('plan')->where('user_id' , $userid)->get();

    	return response()->json(['success' => 1, "message" => 'Subscribed History!!' , "data" =>$subscription])->setStatusCode(200);
    }

    public function createSubscription(Request $request){
    	$stripe = new \Stripe\StripeClient(
		  env('STRIPE_SECRET_KEY')
		);
		$customer_id = $request->customer_id;
    	$subscription = $stripe->paymentIntents->create([
		  'amount' => $request->amount,
		  'currency' => 'AED',
		  'payment_method_types' => ['card'],
		]);
		// echo '<pre>';print_r($subscription);die;
	        // if(count($subscription) > 1){
        //       $subscription = $subscription[0];
        //   }
        if($subscription->id != NULL){
            $clientSecret = $subscription->client_secret;
            $status= $subscription->status;
        }else{
            $clientSecret = "";
            $status = '';
        }
	    
	    return  response()->json(['success' => 1, "message" => 'subscription Created Successfully!!' , "data" =>['subscriptionId' => $subscription->id,'clientSecret'=>$clientSecret , "status" =>$status ]])->setStatusCode(200);
    }
}
