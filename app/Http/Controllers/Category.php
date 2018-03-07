<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Category extends Controller
{
    public function category($category_name){
        $category = \App\Category::where('name', '=', $category_name) -> get() -> first();
        $references   = [];
        for($i = 0; $i < count($category -> topics); $i++){
            $references[$i] = $category -> topics[$i] -> references;
        }
        $topics  = $category -> topics;
        return view('category', compact(['category_name', 'topics', 'references']));
    }
}
