<?php

namespace App\Http\Controllers;

use App\Topic;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use File;
use App\Glance;
use App\User;
use DB;
use Log;

class Simulation extends Controller
{
    public function simulation($topic_name){
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
        $path = 'public/'.$category_name.'/'.$topic_name.'/Simulacion/latest/';
        $jsFiles = Storage::allFiles($path.'js/');
        $cssFiles = Storage::allFiles($path.'css/');
        $path = public_path().'/storage/'.$category_name.'/'.$topic_name.'/Simulacion/latest/html/';
        return view('load_simulation', compact(['path','topic_name', 'category_name', 'jsFiles', 'cssFiles']));
    }

    public function updateGlance(Request $request){
        $student = User::where('id', '=', $request -> user_id) -> first() -> student;
        $topic = Topic::where('approved_name', '=', $request -> topic_name) -> orWhere('pending_name', '=', $request -> topic_name) -> first();
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
