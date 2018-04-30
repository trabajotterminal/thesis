<?php

namespace App\Http\Controllers;

use App\Notification;
use App\Topic;
use App\Category;
use App\Creator;
use App\Reference;
use App\User;
use App\Group;
use App\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use File;
use Log;
use DB;
use Mockery\Matcher\Not;
use Illuminate\Support\Facades\Validator;

class Admin extends Controller{
    public function viewNotification($id){
        $notification_id = $id;
        $notification   = Notification::where('id', '=', $notification_id) -> first();
        $user_id = session('user_id');
        if(!$notification || $notification -> recipient_id != $user_id){
            return view('not_found');
        }
        $sender         = User::where('id', '=', $notification -> sender_id) -> first();
        $topic          = Topic::where('id', '=', $notification -> topic_id) -> first();
        $category_topic = "";
        $tags_topic     = "";
        if($topic) {
            $category_topic = Category::where('id', '=', $topic->category_id)->first()->approved_name;
            $tags_topic     = $topic -> tags() -> get();
        }
        $category       = Category::where('id', '=', $notification -> category_id) -> first();
        $reference      = Reference::where('id', '=', $notification -> reference_id) -> first();
        return view('view_notification', compact(['notification', 'sender', 'topic', 'category', 'reference', 'category_topic', 'tags_topic']));
    }

    public function viewTheoryNotification($id){
        $notification       = Notification::where('id', '=', $id) -> first();
        if(!$notification){
            return view('not_found');
        }
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
        $topic_name = $topic -> pending_name ? $topic -> pending_name : $topic -> approved_name;
        return view('view_theory_notification', compact(['xmlFilePath', 'creator_username', 'action', 'notification', 'topic_name']));
    }

    public function viewQuestionnaireNotification($id){
        $notification_id    = $id;
        $notification       = Notification::where('id', '=', $notification_id) -> first();
        if(!$notification){
            return view('not_found');
        }
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
        $topic_name = $topic -> pending_name ? $topic -> pending_name : $topic -> approved_name;
        return view('view_questionnaire_notification', compact(['topic_name', 'category_name', 'creator_username', 'action', 'notification', 'topic_name']));
    }

    public function viewSimulationNotification($id){
        $notification   = Notification::where('id', '=', $id) -> first();
        if(!$notification){
            return view('not_found');
        }
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
            File::cleanDirectory($path_latest);
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
            File::cleanDirectory($path_latest);
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
            File::cleanDirectory($path_latest);
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
          SELECT U.username AS username, U.profile_picture as profile_picture, AVG(M.Points) AS points FROM users U 
          JOIN students S ON U.id=S.user_id 
          JOIN marks M ON M.student_id=S.id 
          GROUP BY M.user_id ORDER BY  AVG(M.Points) DESC;
        ');
        $non_ranked_users = DB::select('
          SELECT U.username as username, U.profile_picture as profile_picture, S.name as school_name, G.name as group_name from users U, groups G, schools S where U.id IN (SELECT user_id FROM students SS WHERE NOT EXISTS (SELECT user_id FROM marks where student_id = SS.id )) and S.id = (select school_id from students where user_id = U.id) and G.id = (select group_id from students where user_id = U.id)
        ');
        return view('users_ranking', compact(['ranked_users', 'non_ranked_users']));
    }

    public function groupsRanking(){
        $ranked_groups = DB::select('
          SELECT G.name AS name , AVG(M.Points) AS points FROM groups G
          JOIN students S ON G.id=S.group_id
          JOIN marks M ON M.student_id=S.id 
          GROUP BY M.group_id ORDER BY AVG(M.Points) DESC;
        ');
        $non_ranked_groups = DB::select('
          SELECT G.name FROM groups G WHERE NOT EXISTS ( SELECT group_id FROM marks where group_id = G.id) 
        ');
        return view('groups_ranking', compact(['ranked_groups', 'non_ranked_groups']));
    }

    public function schoolsRanking(){
        $ranked_schools = DB::select('
          SELECT S.name AS name, AVG(M.Points) AS points FROM schools S
          JOIN students St ON S.id=St.school_id 
          JOIN marks M ON M.student_id=St.id 
          GROUP BY M.school_id ORDER BY AVG(M.Points) DESC;
        ');
        $non_ranked_schools  = DB::select('
          SELECT S.name FROM schools S WHERE NOT EXISTS ( SELECT school_id FROM marks where school_id = S.id);
        ');
        return view('schools_ranking', compact(['ranked_schools', 'non_ranked_schools']));
    }

    public function userStatistics($user_name){
        $user = User::where('username', '=', $user_name) -> first();
        if(!$user){
            return view('not_found');
        }
        $user_id = $user_name;
        return view('user_ranking', compact(['user_id']));
    }

    public function groupStatistics($name){
        $group_name = $name;
        $group = Group::where('name', '=', $group_name) -> first();
        if(!$group){
            return view('not_found');
        }
        return view('group_statistics', compact(['group_name']));
    }

    public function schoolStatistics($name){
        $school_name = $name;
        $school = School::where('name', '=', $school_name) -> first();
        if(!$school){
            return view('not_found');
        }
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
            $categories_array[$i] = $categories[$i] -> approved_name;
            $topics = $categories[$i] -> topics() -> get();
            $topics_array[$i]   = [];
            $percentages[$i]    = [];
            $users_in_group  = DB::select('SELECT * from students where group_id = ?', [$group -> id]);
            $users_in_group_count  = count($users_in_group);
            for($j = 0; $j < count($topics); $j++){
                $seen = DB::select('SELECT COUNT(*) as many FROM glance_student GS, glances G WHERE GS.glance_id = G.id and G.type = ? and GS.student_id IN (select id from students where group_id = ?);', [ 'T', $group -> id]);
                $seen = $seen[0] -> many;
                $people[$i][$j] = $seen;
                $visualizations += $seen;
                $percentages[$i][$j] = $users_in_group_count > 0 ? $seen * 100 / $users_in_group_count : 0;
                $percentages[$i][$j] = number_format((float)$percentages[$i][$j], 2, '.', '');
                $topics_array[$i][$j] = $topics[$j] -> approved_name;
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
            $categories_array[$i] = $categories[$i] -> approved_name;
            $topics = $categories[$i] -> topics() -> get();
            $topics_array[$i]   = [];
            $percentages[$i]    = [];
            $users_in_group  = DB::select('SELECT * from students where group_id = ?', [$group -> id]);
            $users_in_group_count  = count($users_in_group);
            for($j = 0; $j < count($topics); $j++){
                $seen = DB::select('SELECT COUNT(*) as many FROM glance_student GS, glances G WHERE GS.glance_id = G.id and G.type = ? and GS.student_id IN (select id from students where group_id = ?);', [ 'C', $group -> id]);
                $seen = $seen[0] -> many;
                $people[$i][$j] = $seen;
                $visualizations += $seen;
                $percentages[$i][$j] = $users_in_group_count > 0 ? $seen * 100 / $users_in_group_count : 0;
                $percentages[$i][$j] = number_format((float)$percentages[$i][$j], 2, '.', '');
                $topics_array[$i][$j] = $topics[$j] -> approved_name;
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
            $categories_array[$i] = $categories[$i] -> approved_name;
            $topics = $categories[$i] -> topics() -> get();
            $topics_array[$i]   = [];
            $percentages[$i]    = [];
            $users_in_group  = DB::select('SELECT * from students where group_id = ?', [$group -> id]);
            $users_in_group_count  = count($users_in_group);
            for($j = 0; $j < count($topics); $j++){
                $seen = DB::select('SELECT COUNT(*) as many FROM glance_student GS, glances G WHERE GS.glance_id = G.id and G.type = ? and GS.student_id IN (select id from students where group_id = ?);', [ 'S', $group -> id]);
                $seen = $seen[0] -> many;
                $people[$i][$j] = $seen;
                $visualizations += $seen;
                $percentages[$i][$j] = $users_in_group_count > 0 ? $seen * 100 / $users_in_group_count : 0;
                $percentages[$i][$j] = number_format((float)$percentages[$i][$j], 2, '.', '');
                $topics_array[$i][$j] = $topics[$j] -> approved_name;
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
            $categories_array[$i] = $categories[$i] -> approved_name;
            $topics = $categories[$i] -> topics() -> get();
            $topics_array[$i]   = [];
            $percentages[$i]    = [];
            $users_in_school  = DB::select('SELECT * from students where school_id = ?', [$school -> id]);
            $users_in_school_count  = count($users_in_school);
            for($j = 0; $j < count($topics); $j++){
                $seen = DB::select('SELECT COUNT(*) as many FROM glance_student GS, glances G WHERE GS.glance_id = G.id and G.type = ? and GS.student_id IN (select id from students where school_id = ?);', ['T', $school -> id]);
                $seen = $seen[0] -> many;
                $people[$i][$j] = $seen;
                $visualizations += $seen;
                $percentages[$i][$j] = $users_in_school_count > 0 ? $seen * 100 / $users_in_school_count : 0;
                $percentages[$i][$j] = number_format((float)$percentages[$i][$j], 2, '.', '');
                $topics_array[$i][$j] = $topics[$j] -> approved_name;
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
            $categories_array[$i] = $categories[$i] -> approved_name;
            $topics = $categories[$i] -> topics() -> get();
            $topics_array[$i]   = [];
            $percentages[$i]    = [];
            $users_in_school  = DB::select('SELECT * from students where school_id = ?', [$school -> id]);
            $users_in_school_count  = count($users_in_school);
            for($j = 0; $j < count($topics); $j++){
                $seen = DB::select('SELECT COUNT(*) as many FROM glance_student GS, glances G WHERE GS.glance_id = G.id and G.type = ? and GS.student_id IN (select id from students where school_id = ?);', ['C', $school -> id]);
                $seen = $seen[0] -> many;
                $people[$i][$j] = $seen;
                $visualizations += $seen;
                $percentages[$i][$j] = $users_in_school_count > 0 ? $seen * 100 / $users_in_school_count : 0;
                $percentages[$i][$j] = number_format((float)$percentages[$i][$j], 2, '.', '');
                $topics_array[$i][$j] = $topics[$j] -> approved_name;
                $total_topics++;
            }
        }
        return view('school_statistics_questionnaire_table', compact(['categories_array', 'topics_array', 'percentages', 'people', 'users_in_group_count', 'visualizations']));
    }

    public function getSchoolSimulationStatistics($name){
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
            $categories_array[$i] = $categories[$i] -> approved_name;
            $topics = $categories[$i] -> topics() -> get();
            $topics_array[$i]   = [];
            $percentages[$i]    = [];
            $users_in_school  = DB::select('SELECT * from students where school_id = ?', [$school -> id]);
            $users_in_school_count  = count($users_in_school);
            for($j = 0; $j < count($topics); $j++){
                $seen = DB::select('SELECT COUNT(*) as many FROM glance_student GS, glances G WHERE GS.glance_id = G.id and G.type = ? and GS.student_id IN (select id from students where school_id = ?);', ['S', $school -> id]);
                $seen = $seen[0] -> many;
                $people[$i][$j] = $seen;
                $visualizations += $seen;
                $percentages[$i][$j] = $users_in_school_count > 0 ? $seen * 100 / $users_in_school_count : 0;
                $percentages[$i][$j] = number_format((float)$percentages[$i][$j], 2, '.', '');
                $topics_array[$i][$j] = $topics[$j] -> approved_name;
                $total_topics++;
            }
        }
        return view('group_statistics_simulation_table', compact(['categories_array', 'topics_array', 'percentages', 'people', 'users_in_group_count', 'visualizations']));
    }

    public function creatorsInterface(){
        return view('admin_creators_interface');
    }

    public function creatorList(){
        $users = Creator::all();
        $user_names     = [];
        $user_emails    = [];
        for($i = 0; $i < count($users); $i++){
            $user_names[$i] = User::where('id', '=', $users[$i] -> user_id) -> first() -> username;
            $user_emails[$i] = User::where('id', '=', $users[$i] -> user_id) -> first() -> email;
        }
        return view('admin_creator_list', compact(['user_names', 'user_emails']));
    }

    public function registerCreator(Request $request){
        Validator::extend('new_username', function($field,$value,$parameters){
            $user = User::where('username', '=', $value) -> first();
            return $user ==  null;
        });

        Validator::extend('new_email', function($field,$value,$parameters){
            $user = User::where('email', '=', $value) -> first();
            return $user ==  null;
        });

        $messages = array(
            'username.required'             => 'El nombre de usuario es requerido.',
            'email.required'                => 'El correo electrónico es requerido.',
            'new_username'                  => 'El nombre de usuario introducido ya existe.',
            'new_email'                     => 'El correo electrónico introducido ya existe.',
            'email'                         => 'Introduce un correo válido.',
            'username.regex'                => 'Solo se permiten números y letras para el nombre de usuario.',
        );

        $validator = Validator::make($request->all(), [
            'username' => 'required|new_username|regex:/^[a-zA-Z0-9_.-]*$/|max:255|regex:/^\S*$/',
            'email' => 'required|new_email|email',
        ], $messages);
        if ($validator->passes()) {
            $profile_picture = "data:image/png;base64,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";
            $pass =  substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') , 0 , 6 );
            $user    = new User(['username' => $request -> username, 'password' => bcrypt($pass), 'email' => $request -> email, 'profile_picture' => $profile_picture]);
            $user       -> save();
            $creator    = new Creator(['user_id' => $user -> id]);
            $creator    -> save();
            $msg = "¡Hola!\nEl administrador del sitio http://escom-ipn.hosting.acm.org/algorithms-public te ha creado una cuenta para nuestra plataforma de aprendizaje de algoritmos :)\nUsuario: ". $user -> username."\n"."Contraseña: ".$pass;
            $msg = wordwrap($msg,70);
            mail($user -> email,"Creación de usuario", $msg);
            return response()->json(['success'=>'OK.']);
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }
}
