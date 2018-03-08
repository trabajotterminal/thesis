<?php

namespace App\Http\Controllers;

use App\Mark;
use Illuminate\Http\Request;
use App\Topic;
use App\Category;
use App\User;
use App\Glance;
Use DB;

class Questionnaire extends Controller
{
    public function showQuestionnaire($id){
        $topic_name = $id;
        $topic = Topic::where('name', '=', $topic_name) -> first();
        $category = Category::where('id', '=', $topic -> category_id) -> first();
        $category_name = $category -> name;
        $user_id = session('user');
        $user_marks = User::where('id', '=', $user_id) -> first() -> marks() -> where('topic_id', '=', $topic -> id) -> first();
        $tries = 0;
        if($user_marks){
            $tries = $user_marks -> try_number;
        }
        return view('questionnaire', compact(['topic_name', 'category_name', 'tries']));
    }

    public function getAnswers(Request $request){
        $topic_name     = $request -> topic_name;
        $topic          = Topic::where('name', '=', $topic_name) -> first();
        $category       = Category::where('id', '=', $topic -> category_id) -> first();
        $xmlstring      = file_get_contents('storage/'.$category -> name.'/'.$topic -> name.'/Cuestionario/cuestionario.xml');
        $try_number     = $request -> tries;
        $xml            = simplexml_load_string($xmlstring);
        $right_answers  = [];
        $i = 0;
        $try_number = (int)$try_number;
        if($try_number < $xml['cuestionarios']){
            for($i = 0; $i < count($xml->children()[$try_number]); $i++) {
                for($j = 0; $j < count($xml -> children()[$try_number] -> bloque[$i] -> opcion); $j++){
                    if($xml -> children()[$try_number] -> bloque[$i] -> opcion[$j]['value'] == 'true'){
                        array_push($right_answers, $j + 1);
                        break;
                    }
                }
            }
        }
        return response() -> json(['success' => $right_answers]);
    }

    public function evaluate(Request $request){
        $user_answers   = $request -> user_answers;
        $topic_name     = $request -> topic_name;
        $right_answers  = $request -> right_answers;
        $total = count($right_answers);
        $correct = 0;
        for($i = 0; $i < count($user_answers); $i++){
            if($user_answers[$i] == $right_answers[$i])
                ++$correct;
        }
        $points = ($correct * 10.0) / ($total * 1.0);
        $user_id = session('user');
        $user = User::where('id', '=', $user_id) -> first();
        $topic = Topic::where('name', '=', $request -> topic_name) -> first();
        $mark = User::where('id', '=', $user -> id) -> first() -> marks() -> where('topic_id', '=',$topic -> id) -> first();
        if($mark == null){
            $mark = new Mark(['try_number' => 1, 'points' => $points, 'user_id' => $user -> id, 'group_id' => $user -> group_id, 'school_id' => $user -> school_id, 'topic_id' => $topic -> id, 'category_id' => $topic -> category_id]);
        }else{
            $mark -> try_number = $mark -> try_number + 1;
            $mark -> points = $points;
        }
        $mark -> save();
        $glance = Glance::firstOrNew(array(
            'type'          => 'C',
            'topic_id'      => $topic -> id,
            'category_id'   => $topic -> category_id,
        ));
        $glance -> save();
        $hasGlance = DB::select('SELECT G.type FROM glances G, glance_user Gu where Gu.user_id = ? and G.type = ? and G.topic_id = ? and G.id = Gu.glance_id', [$user -> id, 'C', $topic -> id]);
        if(count($hasGlance) == 0){
            $user -> glances() -> attach($glance -> id, [
                'user_id' => $user ->id,
                'group_id' => $user -> group_id,
                'school_id' => $user -> school_id,
                'topic_id' => $topic -> id,
                'category_id' => $topic -> category_id
            ]);
        }
        return response() -> json(['success' => $mark]);
    }
}
