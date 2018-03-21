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
        $topic = Topic::where('name','=', $topic_name) -> get() -> first();
        $category_id = $topic -> category_id;
        $category = Category::where('id', '=', $category_id) -> get() -> first();
        $category_name = $category -> name;
        $path = 'public/'.$category_name.'/'.$topic_name.'/Teoria/';
        $topic_name = $topic -> name;
        $xmlFile = Storage::allFiles($path);
        return view('theory', compact(['xmlFile', 'topic_name']));
    }

    public function updateGlance(Request $request){
        $student = User::where('id', '=', $request -> user_id) -> first() -> student;
        $topic = Topic::where('name', '=', $request -> topic_name) -> first();
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
