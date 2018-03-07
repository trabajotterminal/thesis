<?php

namespace App\Http\Controllers;

use App\Topic;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Simulation extends Controller
{
    public function simulation($topic_name){
        $topic = Topic::where('name','=', $topic_name) -> get() -> first();
        $category_id = $topic -> category_id;
        $category = Category::where('id', '=', $category_id) -> get() -> first();
        $category_name = $category -> name;
        $path = 'public/'.$category_name.'/'.$topic_name.'/Simulacion/';
        $jsFiles = Storage::allFiles($path.'js/');
        $cssFiles = Storage::allFiles($path.'css/');
        return view('simulation', compact(['jsFiles', 'cssFiles']));
    }
}
