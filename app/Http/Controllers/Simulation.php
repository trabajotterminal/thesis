<?php

namespace App\Http\Controllers;

use App\Topic;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use File;
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
}
