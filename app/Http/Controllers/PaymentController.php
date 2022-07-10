<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Error\Card;
use Cartalyst\Stripe\Stripe;
use App\Models\User;

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
    			$card_token = $request->token;

				// Create customer
				$customer = $stripe->customers->create([
				  'name' => $user->name." ".$user->last_name,
				  'email' => $user->email,
				  'phone' => $user->mobile_no,
				  'description' => 'Sectra App User',
				]);
				$user->customer_id = $customer->id;
				$user->save();

				// save card details

				$card_response = $stripe->customers->createSource(
		          $customer->id,
		          [
		            'source' => $card_token
		          ]
		        );

		        
				// Make payment

				$subscribe = Stripe\Charge::create ([
	                "amount" => intval($request->amount) * 100,
	                "currency" => "AED",
	                "source" => $request->token,
	                "description" => "Sectra Plan"
		        ]);

		        return response()->json(['success' => 1, "message" => 'Subscribed Successfully!!' , "data" =>$subscribe])->setStatusCode(200);
    		// }
    	}
    }
}
