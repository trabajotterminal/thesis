<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Category;
use App\Topic;
use App\Glance;
use App\User;
Use DB;
use Log;

class Theory extends Controller
{
    public function theory($topic_name){
        $topic = Topic::where('approved_name','=', $topic_name) -> orWhere('pending_name', '=', $topic_name) -> get() -> first();
        if(!$topic){
            return view('not_found');
        }
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
        $path = 'public/'.$category_name.'/'.$topic_name.'/Teoria/latest/';
        $xmlFile = Storage::allFiles($path);
        return view('theory', compact(['xmlFile', 'topic_name']));
    }

    public function updateGlance(Request $request){
        $student = User::where('id', '=', $request -> user_id) -> first() -> student;
        $topic = Topic::where('pending_name', '=', $request -> topic_name) ->orWhere('approved_name', '=', $request -> topic_name) -> first();
        $glance = Glance::firstOrNew(array(
            'type'          => $request -> type,
            'topic_id'      => $topic -> id,
            'category_id'   => $topic -> category_id,
        ));
        $glance -> save();
        $hasGlance = DB::select('SELECT G.type FROM glances G, glance_student Gu where Gu.student_id = ? and G.type = ? and G.topic_id = ? and G.id = Gu.glance_id', [$student -> id, $request -> type, $topic -> id]);
        if(count($hasGlance) == 0){
            $student -> glances() -> attach($glance -> id);
        }
        return response() -> json(['success' => 'Glance saved.']);
    }
}
