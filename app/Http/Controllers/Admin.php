<?php

namespace App\Http\Controllers;

use App\Notification;
use App\Topic;
use App\Category;
use App\Reference;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function viewTheoryNotification($id){
        $notification       = Notification::where('id', '=', $id) -> first();
        $action = "";
        if($notification -> type == 'A'){
            $action = 'creada';
        }
        if($notification -> type == 'E'){
            $action = 'actualizada';
        }
        if($notification -> type == 'D'){
            $action = 'eliminada';
        }
        $creator_user_id    = $notification -> sender_id;
        $creator_username   = User::where('id', '=', $creator_user_id) -> first() -> username;
        $reference          = Reference::where('id', '=', $notification -> reference_id) -> first();
        $topic_object       = Topic::where('id', '=', $reference -> topic_id) -> first();
        $topic              = Topic::where('pending_name','=', $topic_object -> pending_name) -> orWhere('approved_name', '=', $topic_object -> approved_name) -> get() -> first();
        $category_id        = $topic -> category_id;
        $category           = Category::where('id', '=', $category_id) -> get() -> first();
        $category_path      = "";
        $topic_path         = "";
        if($category -> needs_approval || $category -> is_approval_pending){
            $category_path = $category -> pending_name;
        }else{
            $category_path = $category -> approved_name;
        }
        if($topic -> needs_approval || $topic -> is_approval_pending){
            $topic_path = $topic -> pending_name;
        }else{
            $topic_path = $topic -> approved_name;
        }
        $path = 'public/'.$category_path.'/'.$topic_path.'/Teoria/changes/';
        $xmlFilePath = Storage::allFiles($path);
        return view('view_theory_notification', compact(['xmlFilePath', 'creator_username', 'action', 'notification']));
    }

    public function viewQuestionnaireNotification($id){
        $notification_id    = $id;
        $notification       = Notification::where('id', '=', $notification_id) -> first();
        $reference          = Reference::where('id', '=', $notification -> reference_id) -> first();
        $topic              = Topic::where('id', '=', $reference -> topic_id) -> first();
        $category           = Category::where('id', '=', $topic -> category_id) -> first();
        $category_path      = "";
        $topic_path         = "";
        if($category -> needs_approval || $category -> is_approval_pending){
            $category_path = $category -> pending_name;
        }else{
            $category_path = $category -> approved_name;
        }
        if($topic -> needs_approval || $topic -> is_approval_pending){
            $topic_path = $topic -> pending_name;
        }else{
            $topic_path = $topic -> approved_name;
        }
        $topic_name = $topic_path;
        $category_name = $category_path;
        $creator_user_id    = $notification -> sender_id;
        $creator_username   = User::where('id', '=', $creator_user_id) -> first() -> username;
        $action = "";
        if($notification -> type == 'A'){
            $action = 'creado';
        }
        if($notification -> type == 'E'){
            $action = 'actualizado';
        }
        if($notification -> type == 'D'){
            $action = 'eliminado';
        }
        return view('view_questionnaire_notification', compact(['topic_name', 'category_name', 'creator_username', 'action', 'notification']));
    }

    public function viewSimulationNotification($id){
        $notification   = Notification::where('id', '=', $id) -> first();
        $creator_user_id    = $notification -> sender_id;
        $creator_username   = User::where('id', '=', $creator_user_id) -> first() -> username;
        $action = "";
        if($notification -> type == 'A'){
            $action = 'creada';
        }
        if($notification -> type == 'E'){
            $action = 'actualizada';
        }
        if($notification -> type == 'D'){
            $action = 'eliminada';
        }
        $reference      = Reference::where('id', '=', $notification -> reference_id) -> first();
        $topic = Topic::where('id','=', $reference -> topic_id) -> get() -> first();
        $category_id = $topic -> category_id;
        $category = Category::where('id', '=', $category_id) -> get() -> first();
        $category_path      = "";
        $topic_path         = "";
        if($category -> needs_approval || $category -> is_approval_pending){
            $category_path = $category -> pending_name;
        }else{
            $category_path = $category -> approved_name;
        }
        if($topic -> needs_approval || $topic -> is_approval_pending){
            $topic_path = $topic -> pending_name;
        }else{
            $topic_path = $topic -> approved_name;
        }
        $category_name  = $category_path;
        $topic_name     = $topic_path;
        $path = 'public/'.$category_path.'/'.$topic_path.'/Simulacion/changes/';
        $jsFiles = Storage::allFiles($path.'js/');
        $cssFiles = Storage::allFiles($path.'css/');
        $path = public_path().'/storage/'.$category_path.'/'.$topic_path.'/Simulacion/changes/html/';
        return view('view_simulation_notification', compact(['path','topic_name', 'category_name', 'jsFiles', 'cssFiles', 'creator_username', 'action', 'notification']));
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

    public function resolveTheoryNotification(Request $request){
        $notification_id   = $request -> notification_id;
        $message           = $request -> message;
        $action            = $request -> action;
        if($action == 'accept'){
            $notification = Notification::where('id', '=', $notification_id) -> first();
            if($notification -> reference_id){
                $reference = Reference::where('id', '=', $notification -> reference_id) -> first();
                $reference -> needs_approval = false;
                $reference -> is_approval_pending = false;
                $reference -> approved_route = $reference-> pending_route;
                $reference -> save();
            }
        }else{
            if($action == 'decline'){

            }
        }
        $notification -> delete();
        return response()->json(['success'=>'OK.']);
    }

    public function resolveSimulationNotification(Request $request){
        $notification_id   = $request -> notification_id;
        $message           = $request -> message;
        $action            = $request -> action;
        if($action == 'accept'){
            $notification = Notification::where('id', '=', $notification_id) -> first();
            if($notification -> reference_id){
                $reference = Reference::where('id', '=', $notification -> reference_id) -> first();
                $reference -> needs_approval = false;
                $reference -> is_approval_pending = false;
                $reference -> approved_route = $reference-> pending_route;
                $reference -> save();
            }
        }else{
            if($action == 'decline'){

            }
        }
        $notification -> delete();
        return response()->json(['success'=>'OK.']);
    }

    public function resolveQuestionnaireNotification(Request $request){
        $notification_id   = $request -> notification_id;
        $message           = $request -> message;
        $action            = $request -> action;
        if($action == 'accept'){
            $notification = Notification::where('id', '=', $notification_id) -> first();
            if($notification -> reference_id){
                $reference = Reference::where('id', '=', $notification -> reference_id) -> first();
                $reference -> needs_approval = false;
                $reference -> is_approval_pending = false;
                $reference -> approved_route = $reference-> pending_route;
                $reference -> save();
            }
        }else{
            if($action == 'decline'){

            }
        }
        $notification -> delete();
        return response()->json(['success'=>'OK.']);
    }
}
