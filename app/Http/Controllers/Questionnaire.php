<?php

namespace App\Http\Controllers;

use App\Mark;
use Illuminate\Http\Request;
use App\Topic;
use App\Category;
use App\User;
use App\Glance;
Use DB;
Use View;

class Questionnaire extends Controller
{
    public function showQuestionnaire($id){
        $topic_name = $id;
        $topic = Topic::where('approved_name', '=', $topic_name) -> orWhere('pending_name', '=', $topic_name) -> first();
        $category = Category::where('id', '=', $topic -> category_id) -> first();
        $user_id = session('user_id');
        $user_mark = User::where('id', '=', $user_id) -> first() -> student() -> first() -> marks() -> where('topic_id', '=', $topic -> id) -> first();
        $tries = 0;
        $category_name = $category -> approved_name;
        if($user_mark){
            $tries = $user_mark -> try_number;
        }
        return view('questionnaire', compact(['topic_name', 'category_name', 'tries']));
    }

    public function getAnswers(Request $request){
        $topic_name     = $request -> topic_name;
        $topic          = Topic::where('approved_name', '=', $topic_name) -> orWhere('pending_name', '=', $topic_name) -> first();
        $category       = Category::where('id', '=', $topic -> category_id) -> first();
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
        $xmlstring      = file_get_contents('storage/'.$category_name.'/'.$topic_name.'/Cuestionario/latest/cuestionario.xml');
        $try_number     = $request -> tries;
        $xml            = simplexml_load_string($xmlstring);
        $right_answers  = [];
        $feedbacks      = [];
        $questions      = [];
        $i = 0;
        $try_number = (int)$try_number;
        if($try_number < $xml['cuestionarios']){
            for($i = 0; $i < count($xml->children()[$try_number]); $i++) {
                array_push($questions, $xml -> children()[$try_number] -> bloque[$i] -> pregunta);
                array_push($feedbacks, $xml -> children()[$try_number] -> bloque[$i] -> retroalimentacion);
                for($j = 0; $j < count($xml -> children()[$try_number] -> bloque[$i] -> opcion); $j++){
                    if($xml -> children()[$try_number] -> bloque[$i] -> opcion[$j]['value'] == 'true'){
                        array_push($right_answers, $j + 1);
                        break;
                    }
                }
            }
        }
        return response() -> json(['answers' => $right_answers, 'feedbacks' => $feedbacks, 'questions' => $questions]);
    }

    public function evaluate(Request $request){
        $user_answers   = $request -> user_answers;
        $topic_name     = $request -> topic_name;
        $right_answers  = $request -> right_answers;
        $questions      = $request -> questions;
        $feedbacks      = $request -> feedbacks;
        $feedbacks_to_display = [];
        $questions_to_display = [];
        $right_answers_to_display = [];
        $total = count($right_answers);
        $correct = 0;
        for($i = 0; $i < count($user_answers); $i++){
            if($user_answers[$i] == $right_answers[$i]) {
                ++$correct;
            }else{
                array_push($questions_to_display, $questions[$i]);
                array_push($feedbacks_to_display, $feedbacks[$i]);
                array_push($right_answers_to_display, $right_answers[$i]);
            }
        }
        $points = ($correct * 10.0) / ($total * 1.0);
        $user_id = session('user_id');
        $student = User::where('id', '=', $user_id) -> first() -> student;
        $topic = Topic::where('approved_name', '=', $request -> topic_name) -> orWhere('pending_name', '=', $request -> topic_name)-> first();
        $mark = User::where('id', '=', $user_id) -> first() -> student() -> first() -> marks() -> where('topic_id', '=', $topic -> id) -> first();
        if($mark == null){
            $mark = new Mark(['try_number' => 0, 'points' => $points, 'user_id' => $user_id, 'student_id' => $student -> id, 'group_id' => $student -> group_id, 'school_id' => $student -> school_id, 'topic_id' => $topic -> id, 'category_id' => $topic -> category_id]);
            $mark -> save();
        }else{
            $mark -> try_number = $mark -> try_number;
            $mark -> points = $points;
            $mark -> save();
        }
        $glance = Glance::firstOrNew(array(
            'type'          => 'C',
            'topic_id'      => $topic -> id,
            'category_id'   => $topic -> category_id,
        ));
        $glance -> save();
        $hasGlance = DB::select('SELECT G.type FROM glances G, glance_student Gu where Gu.student_id = ? and G.type = ? and G.topic_id = ? and G.id = Gu.glance_id', [$student -> id, 'C', $topic -> id]);
        if(count($hasGlance) == 0){
            $student -> glances() -> attach($glance -> id);
        }
        return response() -> json(['view' => View::make('display_feedback', ['user_answers' => $user_answers, 'right_answers' => $right_answers_to_display, 'feedbacks' => $feedbacks_to_display, 'questions' => $questions_to_display, 'topic_name' => $topic_name]) -> render()]);
    }
}
