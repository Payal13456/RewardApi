<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function notificationList(Request $request){
    	$list = Notification::with('user')->where('received_id',$request->user()->id)->where('is_read',0)->get();
    	return response()->json(['success' => 1, "message" => 'Notification List' , "data" =>$list])->setStatusCode(200);
    }
}
