<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Category;
use \App\Topic;
use Illuminate\Support\Traits\CapsuleManagerTrait;
use \App\Reference;
use \App\User;
use Monolog\Processor\TagProcessor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
Use DB;


class Admin extends Controller{
    public function categories(){
        return view('AdminCategories');
    }

    public function topics(){
        $C = Category::all();
        $categories = [];
        for($i = 0; $i < count($C); $i++){
            $categories[$i] = $C[$i] -> name;
        }
        return view('AdminTopics', compact('categories'));
    }

    public function statistics(){
        return view('general_statistics');
    }

    public function usersRanking(){
        $users = DB::select('SELECT U.name AS Usuario, U.lastname AS Apellidos, AVG(P.points) AS PuntajeFinal FROM schools E JOIN groups G ON E.id=G.school_id JOIN users U ON G.id=U.group_id JOIN marks P ON U.id=P.user_id GROUP BY P.user_id ORDER BY AVG(points) DESC ');
        return view('users_ranking', compact('users'));
    }

    public function groupsRanking(){
        $groups = DB::select('SELECT G.name AS Grupo, AVG(P.points) AS PuntajeFinal FROM groups G JOIN users U ON G.id=U.group_id JOIN marks P ON U.id=P.user_id GROUP BY P.group_id ORDER BY AVG(points) DESC ');
        return view('groups_ranking', compact('groups'));
    }

    public function schoolsRanking(){
        $schools = DB::select('SELECT E.name AS Escuela, AVG(P.points) AS PuntajeFinal FROM schools E JOIN groups G ON E.id=G.school_id JOIN users U ON G.id=U.group_id JOIN marks P ON U.id=P.user_id GROUP BY P.school_id ORDER BY AVG(points) DESC;');
        return view('schools_ranking', compact('schools'));
    }

    public function categoryList(){
        $categories = Category::all();
        $names = [];
        for($i = 0; $i < count($categories); $i++){
            $names[$i] = $categories[$i] -> name;
        }
        return view('admin_category_list', compact('names'));
    }

    public function topicList(){
        $T = Topic::all();
        $topics = [];
        $topics_categories = [];
        $categories = [];
        for($i = 0; $i < count($T); $i++){
            $topics[$i]             = $T[$i] -> name;
            $topics_categories[$i]  = Category::where('id', '=', $T[$i] -> category_id) -> first() -> name;
        }
        $C = Category::all();
        $categories = [];
        for($i = 0; $i < count($C); $i++){
            $categories[$i] = $C[$i] -> name;
        }
        return view('admin_topic_list', compact(['topics', 'topics_categories', 'categories']));
    }

    public function categoryListJSON(){
        $categories = Category::all();
        return compact('categories');
    }

    public function registerCategory(Request $request){
        Validator::extend('new_category', function($field,$value,$parameters){
            $category = Category::where('name', '=', $value) -> first();
            return $category ==  null;
        });

        Validator::extend('alpha_spaces', function($attribute, $value)
        {
            return preg_match('/^[\pL\s]+$/u', $value);
        });

        $messages = array(
            'required'              => 'El nombre de la categoria es requerido',
            'new_category'          => 'Nombre existente, elige otro.',
            'alpha_spaces'          => 'Solo se permiten letras y espacios.'
        );

        $validator = Validator::make($request->all(), [
            'category_name' => 'required|new_category|alpha_spaces',
        ], $messages);

        if ($validator->passes()) {
            $category = new Category(['name' => $request -> category_name]);
            $category -> save();
            Storage::disk('local') -> makeDirectory('public/'.$category -> name);
            return response()->json(['success'=>'OK.']);
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function registerTopic(Request $request){

        Validator::extend('new_topic', function($field,$value,$parameters){
            $topic = Topic::where('name', '=', $value) -> first();
            return $topic ==  null;
        });

        Validator::extend('not_default', function($field,$value,$parameters){
            return $value != 'Selecciona la categoria';
        });

        Validator::extend('alpha_spaces', function($attribute, $value)
        {
            return preg_match('/^[\pL\s]+$/u', $value);
        });

        $messages = array(
            'required'              => 'El nombre del tema es requerido',
            'new_topic'             => 'Nombre existente, elige otro.',
            'alpha_spaces'          => 'Solo se permiten letras y espacios.',
            'not_default'           => 'Selecciona una categoria',
        );

        $validator = Validator::make($request->all(), [
            'topic_name'    => 'required|new_topic|alpha_spaces',
            'category_name' => 'not_default',
        ], $messages);

        if ($validator->passes()) {
            $category = Category::where('name', '=', $request->category_name)->first();
            $topic = new Topic(['name' => $request->topic_name, 'category_id' => $category->id]);
            $topic->save();
            Storage::disk('local')->makeDirectory('public/' . $category->name . '/' . $topic->name);
            Storage::disk('local')->makeDirectory('public/' . $category->name . '/' . $topic->name . '/Simulacion');
            Storage::disk('local')->makeDirectory('public/' . $category->name . '/' . $topic->name . '/Simulacion/js');
            Storage::disk('local')->makeDirectory('public/' . $category->name . '/' . $topic->name . '/Simulacion/css');
            Storage::disk('local')->makeDirectory('public/' . $category->name . '/' . $topic->name . '/Teoria');
            Storage::disk('local')->makeDirectory('public/' . $category->name . '/' . $topic->name . '/Cuestionario');
            return response()->json(['success'=>'OK.']);

        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function deleteCategory(Request $request){
        $category = Category::where('name', '=', $request -> category_name) -> first();
        $category -> delete();
        Storage::disk('local') -> deleteDirectory('public/'.$category -> name);
        return response() -> json(['success' => 'OK']);
    }

    public function deleteTopic(Request $request){
        $topic = Topic::where('name', '=', $request -> topic_name) -> first();
        $category = Category::where('id', '=', $topic->category_id) -> first();
        $topic -> delete();
        Storage::disk('local') -> deleteDirectory('public/'. $category -> name. '/'.$topic -> name);
        return response() -> json(['success' => 'OK']);
    }

    public function editCategory(Request $request){
        Validator::extend('new_category', function($field,$value,$parameters){
            $old = $parameters[0];
            $new = $parameters[1];
            $category = Category::where('name', '=', $new) -> first();
            return $old == $new || $category == null;
        });

        Validator::extend('alpha_spaces', function($attribute, $value)
        {
            return preg_match('/^[\pL\s]+$/u', $value);
        });
        $messages = array(
            'required'              => 'El nombre de la categoria es requerido',
            'new_category'          => 'Nombre existente, elige otro.',
            'alpha_spaces'          => 'Solo se permiten letras y espacios.'
        );
        $old = $request -> category_name;
        $new = $request -> new_category_name;
        $validator = Validator::make($request->all(), [
            'new_category_name' => "required|new_category:$old,$new|alpha_spaces",
        ], $messages);
        if ($validator->passes()) {
            $category = Category::where('name', '=', $request -> category_name)->first();
            $category->update(['name' => $request->new_category_name]);
            $category->save();
            if($old != $new)
                Storage::move('public/' . $request->category_name, 'public/' . $category->name);
            return response()->json(['success'=>'OK.']);
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function editTopic(Request $request){
        $old_topic_name         = $request -> topic_name;
        $topic                  = Topic::where('name','=', $old_topic_name) -> first();
        $old_category           = Category::where('id', '=', $topic -> category_id) -> first();
        $category               = Category::where('name', '=', $request -> new_category_name) -> first();

        Validator::extend('alpha_spaces', function($attribute, $value)
        {
            return preg_match('/^[\pL\s]+$/u', $value);
        });

        Validator::extend('not_default', function($field,$value,$parameters){
            return $value != 'Selecciona la categoria';
        });

        Validator::extend('valid_new_topic', function($field, $value, $parameters,  $validator){
            $new_topic_name = $parameters[0];
            $old_topic_name = $parameters[1];
            $topic = Topic::where('name', '=',  $new_topic_name) -> first();
            return $new_topic_name == $old_topic_name || $topic == null;
        });

        $messages = array(
            'required'              => 'El nombre del tema es requerido.',
            'not_default'           => 'Selecciona una categoria.',
            'alpha_spaces'          => 'Solo se permiten letras y espacios.',
            'valid_new_topic'       => 'El nombre del tema ya existe, elige otro.'
        );

        $validator = Validator::make($request->all(), [
            'new_topic_name' => "required|alpha_spaces|valid_new_topic:$topic->name,$old_topic_name",
            'new_category_name' => 'not_default',
        ], $messages);

        if ($validator->passes()) {
            $topic -> update(['category_id'   => $category -> id, 'name' => $request -> new_topic_name]);
            if($old_topic_name != $topic -> name && $old_category -> name != $category -> name)
                Storage::move('public/' . $old_category->name . '/' . $old_topic_name, 'public/' . $category->name . '/' . $topic->name);
            if($old_topic_name != $topic -> name && $old_category -> name == $category -> name)
                Storage::move('public/' . $old_category->name . '/' . $old_topic_name, 'public/' . $old_category->name . '/' . $topic->name);
            if($old_topic_name == $topic -> name && $old_category -> name != $category -> name)
                Storage::move('public/' . $old_category->name . '/' . $old_topic_name, 'public/' . $category->name . '/' . $old_topic_name);
            $topic -> save();
            return response() -> json(['success' => 'OK']);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function displayTopic($name){
        $topic_name = $name;
        $urls       = [];
        $topic = Topic::where('name', '=', $topic_name) -> first();
        $R = Reference::where('topic_id', '=', $topic ->id) -> get();
        $references = [];
        $references['T'] = false;
        $references['C'] = false;
        $references['S'] = false;
        for($i = 0; $i < count($R); $i++){
            if($R[$i] -> type == 'C')
                $references['C'] = true;
            if($R[$i] -> type == 'T')
                $references['T'] = true;
            if($R[$i] -> type == 'S')
                $references['S'] = true;
        }
        return view('topic', compact(['topic_name', 'references']));
    }

    public function topicTheoryManager($name){
        return view('theorymanager', compact('name'));
    }

    public function topicSimulationManager($name){
        return view('simulationmanager');
    }

    public function topicQuestionnaireManager($name){
        return view('questionnairemanager', compact('name'));
    }

    public function registerTheoryFile(Request $request){
        if ($request->hasFile('input_file')) {
            $topic = Topic::where('name', '=', $request -> topic_name) -> first();
            $category   = Category::where('id', '=', $topic -> category_id) -> first();
            $file = $request -> file('input_file');
            $name = $request -> file('input_file')->getClientOriginalName();
            $destinationPath = public_path('storage/'.$category -> name.'/'.$topic -> name.'/Teoria/');
            $request -> input_file -> move($destinationPath, $name);
            $route = new Reference();
            $route -> type = 'T';
            $route -> route = $destinationPath;
            $route -> category_id = $category -> id;
            $route -> topic_id = $topic -> id;
            $route -> save();
            return redirect('admin/topic/'.$topic->name);
        }
    }

    public function registerQuestionnaireFile(Request $request){
        if ($request->hasFile('input_file')) {
            $topic = Topic::where('name', '=', $request -> topic_name) -> first();
            $category   = Category::where('id', '=', $topic -> category_id) -> first();
            $file = $request -> file('input_file');
            $name = $request -> file('input_file')->getClientOriginalName();
            $destinationPath = public_path('storage/'.$category -> name.'/'.$topic -> name.'/Cuestionario/');
            $request -> input_file -> move($destinationPath, 'cuestionario.xml');
            $route = new Reference();
            $route -> type = 'C';
            $route -> route = $destinationPath;
            $route -> category_id = $category -> id;
            $route -> topic_id = $topic -> id;
            $route -> save();
            return redirect('admin/topic/'.$topic->name);
        }
    }

    public function registerTheoryManually(Request $request){
        $topic_name = $request -> topic_name;
        return view('register_theory_manually', compact('topic_name'));
    }

    public function registerQuestionnaireManually(Request $request){
        $topic_name = $request -> topic_name;
        $topic = Topic::where('name', '=', $topic_name) -> first();
        $category = Category::where('id', '=', $topic -> category_id) -> first();
        $category_name = $category -> name;
        return view('register_questionnaire_manually', compact(['topic_name', 'category_name']));
    }

    public function saveTheoryManually(Request $request){
        $topic = Topic::where('name', '=', $request -> topic_name) -> first();
        $category = Category::where('id', '=', $topic -> category_id) -> first();
        $destination_path = public_path('storage/'.$category -> name.'/'.$topic -> name.'/Teoria/teoria.xml');
        Storage::disk('local')->put('public/'.$category->name.'/'.$topic->name.'/Teoria/teoria.xml', $request -> xmlContent);
        $reference = new Reference();
        $reference -> type = 'T';
        $reference -> route = $destination_path;
        $reference -> category_id = $category -> id;
        $reference -> topic_id = $topic -> id;
        $reference -> save();
        return response() -> json(['success' => $destination_path.' '.$request->xmlContent]);
    }

    public function saveQuestionnaireManually(Request $request){
        $topic = Topic::where('name', '=', $request -> topic_name) -> first();
        $category = Category::where('id', '=', $topic -> category_id) -> first();
        $destination_path = public_path('storage/'.$category -> name.'/'.$topic -> name.'/Cuestionario/cuestionario.xml');
        Storage::disk('local')->put('public/'.$category->name.'/'.$topic->name.'/Cuestionario/cuestionario.xml', $request -> xmlContent);
        $reference = new Reference();
        $reference -> type = 'C';
        $reference -> route = $destination_path;
        $reference -> category_id = $category -> id;
        $reference -> topic_id = $topic -> id;
        $reference -> save();
        return response() -> json(['success' => 'OK']);
    }

}
