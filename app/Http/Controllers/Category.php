<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class Category extends Controller{
    public function category($category_name){
        $category = \App\Category::where('approved_name', '=', $category_name) -> get() -> first();
        $topics  = $category -> topics() -> where('approved_name', '!=', '') -> get();
        $references   = [];
        for($i = 0; $i < count($topics); $i++){
            $references[$i] = $topics[$i] -> references;
        }
        return view('category', compact(['category_name', 'topics', 'references']));
    }
}
