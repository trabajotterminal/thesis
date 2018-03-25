<?php

namespace App\Http\Controllers;

use App\Notification;
use App\Topic;
use App\Category;
use App\Reference;
use App\User;
use Illuminate\Http\Request;

class Admin extends Controller{
    public function viewNotification($id){
        $notification_id = $id;
        $notification = Notification::where('id', '=', $notification_id) -> first();
        $sender       = User::where('id', '=', $notification -> sender_id) -> first();
        $topic        = Topic::where('id', '=', $notification -> topic_id) -> first();
        $category     = Category::where('id', '=', $notification -> category_id) -> first();
        $reference    = Reference::where('id', '=', $notification -> reference_id) -> first();
        return view('view_notification', compact(['notification', 'sender', 'topic', 'category', 'reference']));
    }

    public function resolveNotification(Request $request){
         $notification_id   = $request -> notification_id;
         $message           = $request -> message;
         $action            = $request -> action;
         if($action == 'accept'){
            $notification = Notification::where('id', '=', $notification_id) -> first();
            if($notification -> topic_id){
                $topic      = Topic::where('id', '=', $notification -> topic_id) -> first();
                $topic -> needs_approval = false;
                $topic -> is_approval_pending = false;
                $topic -> approved_name = $topic -> pending_name;
                $topic -> save();
            }
             if($notification -> category_id){
                $category = Category::where('id', '=', $notification -> category_id) -> first();
                $category -> needs_approval = false;
                $category -> is_approval_pending = false;
                $category -> approved_name = $category -> pending_name;
                $category -> save();
             }
             if($notification -> reference_id){

             }
         }else{
             if($action == 'decline'){

             }
         }

        $notification -> delete();
        return response()->json(['success'=>'OK.']);
    }
}
