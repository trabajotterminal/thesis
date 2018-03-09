<?php

namespace App\Http\Controllers;

use App\Topic;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use File;
use App\Glance;
use App\User;
Use DB;

class Simulation extends Controller
{
    public function simulation($topic_name){
        $topic = Topic::where('name','=', $topic_name) -> get() -> first();
        $topic_name = $topic -> name;
        $category_id = $topic -> category_id;
        $category = Category::where('id', '=', $category_id) -> get() -> first();
        $category_name = $category -> name;
        $path = 'public/'.$category_name.'/'.$topic_name.'/Simulacion/';
        $jsFiles = Storage::allFiles($path.'js/');
        $cssFiles = Storage::allFiles($path.'css/');
        $path = public_path().'/storage/'.$category_name.'/'.$topic_name.'/Simulacion/html/';
        return view('load_simulation', compact(['path','topic_name', 'category_name', 'jsFiles', 'cssFiles']));
    }

    public function updateGlance(Request $request){
        $user = User::where('id', '=', $request -> user_id) -> first();
        $topic = Topic::where('name', '=', $request -> topic_name) -> first();
        $glance = Glance::firstOrNew(array(
            'type'          => $request -> type,
            'topic_id'      => $topic -> id,
            'category_id'   => $topic -> category_id,
        ));
        $glance -> save();
        $hasGlance = DB::select('SELECT G.type FROM glances G, glance_user Gu where Gu.user_id = ? and G.type = ? and G.topic_id = ? and G.id = Gu.glance_id', [$user -> id, $request -> type, $topic -> id]);
        if(count($hasGlance) == 0){
            $user -> glances() -> attach($glance -> id, [
                'user_id' => $user ->id,
                'group_id' => $user -> group_id,
                'school_id' => $user -> school_id,
                'topic_id' => $topic -> id,
                'category_id' => $topic -> category_id
            ]);
        }
        return response() -> json(['success' => 'Glance saved.']);
    }
}
