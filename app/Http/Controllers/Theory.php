<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Category;
use App\Topic;
use App\Glance;
use App\User;

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
        $user = User::where('id', '=', $request -> user_id) -> first();
        $topic = Topic::where('name', '=', $request -> topic_name) -> first();
        $glance = Glance::firstOrNew(array(
            'type'          => $request -> type,
            'user_id'       => $request -> user_id,
            'group_id'      => $user -> group_id,
            'school_id'     => $user -> school_id,
            'topic_id'      => $topic -> id,
            'category_id'   => $topic -> category_id,
        ));
        $glance -> save();
        return response() -> json(['success' => 'Glance saved.']);
    }
}
