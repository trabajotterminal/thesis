<?php

namespace App\Http\Controllers;

use App\Notification;
use App\Topic;
use App\Category;
use App\Reference;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use File;
use Log;
use DB;
use Mockery\Matcher\Not;

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
         $notification = Notification::where('id', '=', $notification_id) -> first();
         $notification_to_send = new Notification([
            'message'           => $message,
            'sender_id'         => $notification -> recipient_id,
            'recipient_id'      => $notification -> sender_id,
            'additional_params' => $notification -> type,
         ]);
         $topic = "";
         $category = "";
        if($notification -> topic_id){
            $topic      = Topic::where('id', '=', $notification -> topic_id) -> first();
            $topic -> needs_approval = false;
            $topic -> is_approval_pending = false;
            $notification_to_send -> topic_id = $notification -> topic_id;
        }
        if($notification -> category_id){
            $category = Category::where('id', '=', $notification -> category_id) -> first();
            $category -> needs_approval = false;
            $category -> is_approval_pending = false;
            $notification_to_send -> category_id = $notification -> category_id;
        }
         if($action == 'accept'){
             $notification_to_send -> type = 'MP';
             if($notification -> topic_id)
                $topic -> approved_name = $topic -> pending_name;
             if($notification -> category_id)
                $category -> approved_name = $category -> pending_name;
         }else{
             if($action == 'decline'){
                 $notification_to_send -> type = 'MN';
             }
         }
         if($topic)
            $topic -> save();
         if($category)
            $category -> save();
         $notification -> delete();
         $notification_to_send -> save();
         return response()->json(['success'=>'OK.']);
    }

    public function resolveTheoryNotification(Request $request){
        $notification_id   = $request -> notification_id;
        $message           = $request -> message;
        $action            = $request -> action;
        $notification = Notification::where('id', '=', $notification_id) -> first();

        $reference      = Reference::where('id', '=', $notification -> reference_id) -> first();
        $reference -> needs_approval = false;
        $reference -> is_approval_pending = false;

        $topic = Topic::where('id','=', $reference -> topic_id) -> get() -> first();
        $category_id = $topic -> category_id;
        $category = Category::where('id', '=', $category_id) -> get() -> first();

        $notification_to_send = new Notification([
            'message'           => $message,
            'sender_id'         => $notification -> recipient_id,
            'recipient_id'      => $notification -> sender_id,
            'additional_params' => $notification -> type,
            'reference_id'      => $notification -> reference_id,
        ]);

        if($action == 'accept'){
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
            $path_changes   = 'storage/'.$category_path.'/'.$topic_path.'/Teoria/changes/';
            $path_latest    = 'storage/'.$category_path.'/'.$topic_path.'/Teoria/latest/';
            $reference -> approved_route = $reference-> pending_route;
            File::copyDirectory(public_path($path_changes), public_path($path_latest), true);
            $notification_to_send -> type = "MP";
        }else{
            if($action == 'decline'){
                $notification_to_send -> type = "MN";
            }
        }
        $notification_to_send -> save();
        $reference -> save();
        $notification -> delete();
        return response()->json(['success'=>'OK.']);
    }

    public function resolveSimulationNotification(Request $request){
        $notification_id   = $request -> notification_id;
        $message           = $request -> message;
        $action            = $request -> action;
        $notification = Notification::where('id', '=', $notification_id) -> first();

        $reference      = Reference::where('id', '=', $notification -> reference_id) -> first();
        $reference -> needs_approval = false;
        $reference -> is_approval_pending = false;

        $topic = Topic::where('id','=', $reference -> topic_id) -> get() -> first();
        $category_id = $topic -> category_id;
        $category = Category::where('id', '=', $category_id) -> get() -> first();

        $notification_to_send = new Notification([
            'message'           => $message,
            'sender_id'         => $notification -> recipient_id,
            'recipient_id'      => $notification -> sender_id,
            'additional_params' => $notification -> type,
            'reference_id'      => $notification -> reference_id,
        ]);

        if($action == 'accept'){
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
            $path_changes   = 'storage/'.$category_path.'/'.$topic_path.'/Simulacion/changes/';
            $path_latest    = 'storage/'.$category_path.'/'.$topic_path.'/Simulacion/latest/';
            $reference -> approved_route = $reference-> pending_route;
            File::copyDirectory(public_path($path_changes), public_path($path_latest), true);
            $notification_to_send -> type = "MP";
        }else{
            if($action == 'decline'){
                $notification_to_send -> type = "MN";
            }
        }
        $notification_to_send -> save();
        $reference -> save();
        $notification -> delete();
        return response()->json(['success'=>'OK.']);
    }

    public function resolveQuestionnaireNotification(Request $request){
        $notification_id   = $request -> notification_id;
        $message           = $request -> message;
        $action            = $request -> action;
        $notification = Notification::where('id', '=', $notification_id) -> first();

        $reference      = Reference::where('id', '=', $notification -> reference_id) -> first();
        $reference -> needs_approval = false;
        $reference -> is_approval_pending = false;

        $topic = Topic::where('id','=', $reference -> topic_id) -> get() -> first();
        $category_id = $topic -> category_id;
        $category = Category::where('id', '=', $category_id) -> get() -> first();

        $notification_to_send = new Notification([
            'message'           => $message,
            'sender_id'         => $notification -> recipient_id,
            'recipient_id'      => $notification -> sender_id,
            'additional_params' => $notification -> type,
            'reference_id'      => $notification -> reference_id,
        ]);

        if($action == 'accept'){
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
            $path_changes   = 'storage/'.$category_path.'/'.$topic_path.'/Cuestionario/changes/';
            $path_latest    = 'storage/'.$category_path.'/'.$topic_path.'/Cuestionario/latest/';
            $reference -> approved_route = $reference-> pending_route;
            File::copyDirectory(public_path($path_changes), public_path($path_latest), true);
            $notification_to_send -> type = "MP";
        }else{
            if($action == 'decline'){
                $notification_to_send -> type = "MN";
            }
        }
        $notification_to_send -> save();
        $reference -> save();
        $notification -> delete();
        return response()->json(['success'=>'OK.']);
    }

    /***************************************STATISTICS**********************************************+*/
    public function statistics(){
        return view('general_statistics');
    }

    public function usersRanking(){
        $ranked_users = DB::select('
          SELECT U.username AS username, U.profile_picture as profile_picture, AVG(M.Points) AS points FROM Users U 
          JOIN Students S ON U.id=S.user_id 
          JOIN Marks M ON M.student_id=S.id 
          GROUP BY M.user_id ORDER BY  AVG(M.Points) DESC;
        ');
        $non_ranked_users = DB::select('
          SELECT username, profile_picture from users where id = (SELECT S.id FROM students S WHERE NOT EXISTS ( SELECT user_id FROM marks where student_id = S.id ));
        ');
        return view('users_ranking', compact(['ranked_users', 'non_ranked_users']));
    }

    public function groupsRanking(){
        $ranked_groups = DB::select('
          SELECT G.name AS name , AVG(M.Points) AS points FROM Groups G
          JOIN Students S ON G.id=S.group_id
          JOIN Marks M ON M.student_id=S.id 
          GROUP BY M.group_id ORDER BY AVG(M.Points) DESC;
        ');
        $non_ranked_groups = DB::select('
          SELECT G.name FROM groups G WHERE NOT EXISTS ( SELECT group_id FROM marks where group_id = G.id) 
        ');
        return view('groups_ranking', compact(['ranked_groups', 'non_ranked_groups']));
    }

    public function schoolsRanking(){
        $ranked_schools = DB::select('
          SELECT S.name AS name, AVG(M.Points) AS points FROM Schools S
          JOIN Students St ON S.id=St.school_id 
          JOIN Marks M ON M.student_id=St.id 
          GROUP BY M.school_id ORDER BY AVG(M.Points) DESC;
        ');
        $non_ranked_schools  = DB::select('
          SELECT S.name FROM schools S WHERE NOT EXISTS ( SELECT school_id FROM marks where school_id = S.id);
        ');
        return view('schools_ranking', compact(['ranked_schools', 'non_ranked_schools']));
    }

    public function userStatistics($id){
        $user_id = $id;
        return view('user_ranking', compact(['user_id']));
    }

    public function groupStatistics($name){
        $group_name = $name;
        return view('group_statistics', compact(['group_name']));
    }

    public function schoolStatistics($name){
        $school_name = $name;
        return view('school_statistics', compact(['school_name']));
    }

    public function getUserTheoryStatistics($user){
        $user_name = $user;
        $categories = Category::all();
        $categories_array = [];
        $topics_array = [];
        $total_topics = 0;
        for($i = 0; $i < count($categories); $i++){
            $categories_array[$i] = $categories[$i] -> approved_name;
            $topics = $categories[$i] -> topics() -> get();
            $topics_array[$i] = [];
            for($j = 0; $j < count($topics); $j++){
                $topics_array[$i][$j] = $topics[$j] -> approved_name;
                $total_topics++;
            }
        }
        $theory_glances = User::where('username', '=', $user_name) -> first() -> student -> glances() ->  where('type', '=', 'T') -> get();
        $theory_glances_array = [];
        for($i = 0; $i < count($theory_glances); $i++){
            $topic_name = Topic::where('id', '=', $theory_glances[$i] -> topic_id) -> first();
            $theory_glances_array[$i] = $topic_name;
        }
        return view('user_statistics_theory_table', compact(['user_id', 'categories_array', 'topics_array', 'theory_glances_array', 'total_topics']));
    }

    public function getUserQuestionnaireStatistics($user){
        $user_name = $user;
        $categories = Category::all();
        $categories_array = [];
        $topics_array = [];
        $total_topics = 0;
        for($i = 0; $i < count($categories); $i++){
            $categories_array[$i] = $categories[$i] -> approved_name;
            $topics = $categories[$i] -> topics() -> get();
            $topics_array[$i] = [];
            for($j = 0; $j < count($topics); $j++){
                $topics_array[$i][$j] = $topics[$j] -> approved_name;
                $total_topics++;
            }
        }
        $questionnaire_glances = User::where('username', '=', $user_name) -> first() -> student -> glances() -> where('type', '=', 'C') -> get();
        $questionnaire_glances_array = [];
        for($i = 0; $i < count($questionnaire_glances); $i++){
            $topic_name = Topic::where('id', '=', $questionnaire_glances[$i] -> topic_id) -> first();
            $questionnaire_glances_array[$i] = $topic_name;
        }
        return view('user_statistics_questionnaire_table', compact(['user_id', 'categories_array', 'topics_array', 'questionnaire_glances_array', 'total_topics']));
    }

    public function getUserSimulationStatistics($user){
        $user_name = $user;
        $categories = Category::all();
        $categories_array = [];
        $topics_array = [];
        $total_topics = 0;
        for($i = 0; $i < count($categories); $i++){
            $categories_array[$i] = $categories[$i] -> approved_name;
            $topics = $categories[$i] -> topics() -> get();
            $topics_array[$i] = [];
            for($j = 0; $j < count($topics); $j++){
                $topics_array[$i][$j] = $topics[$j] -> approved_name;
                $total_topics++;
            }
        }
        $simulation_glances = User::where('username', '=', $user_name) -> first() -> student -> glances() -> where('type', '=', 'S') -> get();
        $simulation_glances_array = [];
        for($i = 0; $i < count($simulation_glances); $i++){
            $topic_name = Topic::where('id', '=', $simulation_glances[$i] -> topic_id) -> first();
            $simulation_glances_array[$i] = $topic_name;
        }
        return view('user_statistics_simulation_table', compact(['user_id', 'categories_array', 'topics_array', 'simulation_glances_array', 'total_topics']));
    }

    public function getGroupTheoryStatistics($name){
        $group_name         = $name;
        $group              = Group::where('name', '=', $group_name) -> first();
        $categories         = Category::all();
        $categories_array   = [];
        $topics_array       = [];
        $total_topics       = 0;
        $percentages        = [];
        $people             = [];
        $visualizations     = 0;
        for($i = 0; $i < count($categories); $i++){
            $categories_array[$i] = $categories[$i] -> name;
            $topics = $categories[$i] -> topics() -> get();
            $topics_array[$i]   = [];
            $percentages[$i]    = [];
            $users_in_group  = User::where('group_id', '=', $group -> id) -> get();
            $users_in_group_count  = count($users_in_group);
            for($j = 0; $j < count($topics); $j++){
                $seen = DB::select('SELECT COUNT(*) as many FROM glance_user as GU, glances as G where GU.group_id = ? and G.type = ?  and G.topic_id = ? and G.id = Gu.glance_id', [$group -> id, 'T', $topics[$j] -> id]);
                $seen = $seen[0] -> many;
                $people[$i][$j] = $seen;
                $visualizations += $seen;
                $percentages[$i][$j] = $users_in_group_count > 0 ? $seen * 100 / $users_in_group_count : 0;
                $percentages[$i][$j] = number_format((float)$percentages[$i][$j], 2, '.', '');
                $topics_array[$i][$j] = $topics[$j] -> name;
                $total_topics++;
            }
        }
        return view('group_statistics_theory_table', compact(['categories_array', 'topics_array', 'percentages', 'people', 'visualizations']));
    }

    public function getGroupQuestionnaireStatistics($name){
        $group_name         = $name;
        $group              = Group::where('name', '=', $group_name) -> first();
        $categories         = Category::all();
        $categories_array   = [];
        $topics_array       = [];
        $total_topics       = 0;
        $percentages        = [];
        $people             = [];
        $visualizations     = 0;
        for($i = 0; $i < count($categories); $i++){
            $categories_array[$i] = $categories[$i] -> name;
            $topics = $categories[$i] -> topics() -> get();
            $topics_array[$i]   = [];
            $percentages[$i]    = [];
            $users_in_group  = User::where('group_id', '=', $group -> id) -> get();
            $users_in_group_count  = count($users_in_group);
            for($j = 0; $j < count($topics); $j++){
                $seen = DB::select('SELECT COUNT(*) as many FROM glance_user as GU, glances as G where GU.group_id = ? and G.type = ?  and G.topic_id = ? and G.id = Gu.glance_id', [$group -> id, 'C', $topics[$j] -> id]);
                $seen = $seen[0] -> many;
                $people[$i][$j] = $seen;
                $visualizations += $seen;
                $percentages[$i][$j] = $users_in_group_count > 0 ? $seen * 100 / $users_in_group_count : 0;
                $percentages[$i][$j] = number_format((float)$percentages[$i][$j], 2, '.', '');
                $topics_array[$i][$j] = $topics[$j] -> name;
                $total_topics++;
            }
        }
        return view('group_statistics_questionnaire_table', compact(['categories_array', 'topics_array', 'percentages', 'people', 'users_in_group_count', 'visualizations']));
    }

    public function getGroupSimulationStatistics($name){
        $group_name         = $name;
        $group              = Group::where('name', '=', $group_name) -> first();
        $categories         = Category::all();
        $categories_array   = [];
        $topics_array       = [];
        $total_topics       = 0;
        $percentages        = [];
        $people             = [];
        $visualizations     = 0;
        for($i = 0; $i < count($categories); $i++){
            $categories_array[$i] = $categories[$i] -> name;
            $topics = $categories[$i] -> topics() -> get();
            $topics_array[$i]   = [];
            $percentages[$i]    = [];
            $users_in_group  = User::where('group_id', '=', $group -> id) -> get();
            $users_in_group_count  = count($users_in_group);
            for($j = 0; $j < count($topics); $j++){
                $seen = DB::select('SELECT COUNT(*) as many FROM glance_user as GU, glances as G where GU.group_id = ? and G.type = ?  and G.topic_id = ? and G.id = Gu.glance_id', [$group -> id, 'S', $topics[$j] -> id]);
                $seen = $seen[0] -> many;
                $people[$i][$j] = $seen;
                $visualizations += $seen;
                $percentages[$i][$j] = $users_in_group_count > 0 ? $seen * 100 / $users_in_group_count : 0;
                $percentages[$i][$j] = number_format((float)$percentages[$i][$j], 2, '.', '');
                $topics_array[$i][$j] = $topics[$j] -> name;
                $total_topics++;
            }
        }
        return view('group_statistics_simulation_table', compact(['categories_array', 'topics_array', 'percentages', 'people', 'users_in_group_count', 'visualizations']));
    }


    public function getSchoolTheoryStatistics($name){
        $school_name         = $name;
        $school              = School::where('name', '=', $school_name) -> first();
        $categories         = Category::all();
        $categories_array   = [];
        $topics_array       = [];
        $total_topics       = 0;
        $percentages        = [];
        $people             = [];
        $visualizations     = 0;
        for($i = 0; $i < count($categories); $i++){
            $categories_array[$i] = $categories[$i] -> name;
            $topics = $categories[$i] -> topics() -> get();
            $topics_array[$i]   = [];
            $percentages[$i]    = [];
            $users_in_school  = User::where('school_id', '=', $school -> id) -> get();
            $users_in_school_count  = count($users_in_school);
            for($j = 0; $j < count($topics); $j++){
                $seen = DB::select('SELECT COUNT(*) as many FROM glance_user as GU, glances as G where GU.school_id = ? and G.type = ?  and G.topic_id = ? and G.id = Gu.glance_id', [$school -> id, 'T', $topics[$j] -> id]);
                $seen = $seen[0] -> many;
                $people[$i][$j] = $seen;
                $visualizations += $seen;
                $percentages[$i][$j] = $users_in_school_count > 0 ? $seen * 100 / $users_in_school_count : 0;
                $percentages[$i][$j] = number_format((float)$percentages[$i][$j], 2, '.', '');
                $topics_array[$i][$j] = $topics[$j] -> name;
                $total_topics++;
            }
        }
        return view('school_statistics_theory_table', compact(['categories_array', 'topics_array', 'percentages', 'people', 'visualizations']));
    }

    public function getSchoolQuestionnaireStatistics($name){
        $school_name        = $name;
        $school             = School::where('name', '=', $school_name) -> first();
        $categories         = Category::all();
        $categories_array   = [];
        $topics_array       = [];
        $total_topics       = 0;
        $percentages        = [];
        $people             = [];
        $visualizations     = 0;
        for($i = 0; $i < count($categories); $i++){
            $categories_array[$i] = $categories[$i] -> name;
            $topics = $categories[$i] -> topics() -> get();
            $topics_array[$i]   = [];
            $percentages[$i]    = [];
            $users_in_school  = User::where('group_id', '=', $school -> id) -> get();
            $users_in_school_count  = count($users_in_school);
            for($j = 0; $j < count($topics); $j++){
                $seen = DB::select('SELECT COUNT(*) as many FROM glance_user as GU, glances as G where GU.school_id = ? and G.type = ?  and G.topic_id = ? and G.id = Gu.glance_id', [$school -> id, 'C', $topics[$j] -> id]);
                $seen = $seen[0] -> many;
                $people[$i][$j] = $seen;
                $visualizations += $seen;
                $percentages[$i][$j] = $users_in_school_count > 0 ? $seen * 100 / $users_in_school_count : 0;
                $percentages[$i][$j] = number_format((float)$percentages[$i][$j], 2, '.', '');
                $topics_array[$i][$j] = $topics[$j] -> name;
                $total_topics++;
            }
        }
        return view('school_statistics_questionnaire_table', compact(['categories_array', 'topics_array', 'percentages', 'people', 'users_in_group_count', 'visualizations']));
    }

    public function getSchoolSimulationStatistics($name){
        $schoo         = $name;
        $group              = Group::where('name', '=', $group_name) -> first();
        $categories         = Category::all();
        $categories_array   = [];
        $topics_array       = [];
        $total_topics       = 0;
        $percentages        = [];
        $people             = [];
        $visualizations     = 0;
        for($i = 0; $i < count($categories); $i++){
            $categories_array[$i] = $categories[$i] -> name;
            $topics = $categories[$i] -> topics() -> get();
            $topics_array[$i]   = [];
            $percentages[$i]    = [];
            $users_in_group  = User::where('group_id', '=', $group -> id) -> get();
            $users_in_group_count  = count($users_in_group);
            for($j = 0; $j < count($topics); $j++){
                $seen = DB::select('SELECT COUNT(*) as many FROM glance_user as GU, glances as G where GU.group_id = ? and G.type = ?  and G.topic_id = ? and G.id = Gu.glance_id', [$group -> id, 'S', $topics[$j] -> id]);
                $seen = $seen[0] -> many;
                $people[$i][$j] = $seen;
                $visualizations += $seen;
                $percentages[$i][$j] = $users_in_group_count > 0 ? $seen * 100 / $users_in_group_count : 0;
                $percentages[$i][$j] = number_format((float)$percentages[$i][$j], 2, '.', '');
                $topics_array[$i][$j] = $topics[$j] -> name;
                $total_topics++;
            }
        }
        return view('group_statistics_simulation_table', compact(['categories_array', 'topics_array', 'percentages', 'people', 'users_in_group_count', 'visualizations']));
    }

}
