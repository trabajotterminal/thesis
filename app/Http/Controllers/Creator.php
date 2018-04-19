<?php

namespace App\Http\Controllers;

use App\Group;
use App\Notification;
use App\School;
use App\Tag;
use Illuminate\Http\Request;
use \App\Category;
use \App\Topic;
use Illuminate\Http\Response;
use Illuminate\Support\Traits\CapsuleManagerTrait;
use \App\Reference;
use \App\User;
use \App\Admin;
use Mockery\Tests\React_WritableStreamInterface;
use Monolog\Processor\TagProcessor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
Use DB;
use ZipArchive;
use Log;
use File;
use Illuminate\Support\Facades\Redirect;


class Creator extends Controller{
    public function categories(){
        return view('creator_categories_interface');
    }

    public function topics(){
        //COMMENT: filter by accepted status.
        $C = Category::where('approved_name', '!=', '') -> get();
        $tags = Tag::all();
        $categories = [];
        for($i = 0; $i < count($C); $i++){
            $categories[$i] = $C[$i] -> approved_name;
        }
        return view('creator_topics_interface', compact(['categories', 'tags']));
    }

    public function categoryList(){
        $user_id = session('user_id');
        $categories = Category::where('user_id', '=', $user_id) -> get();
        $names = [];
        $needs_revision = [];
        $has_been_approved_once = [];
        for($i = 0; $i < count($categories); $i++){
            $names[$i] = $categories[$i] -> pending_name;
            $needs_revision[$i] = $categories[$i] -> needs_approval;
            $is_approval_pending[$i] = $categories[$i] -> is_approval_pending;
            $has_been_approved_once[$i] = $categories[$i] -> approved_name ? true : false;
        }
        return view('creator_category_list', compact(['names', 'needs_revision', 'is_approval_pending', 'has_been_approved_once']));
    }

    public function topicList(){
        $user_id                = session('user_id');
        $T                      = Topic::where('user_id', '=',$user_id) -> get();
        $topics                 = [];
        $topics_categories      = [];
        $topics_tags            = [];
        $categories             = [];
        $needs_approval         = [];
        $is_approval_pending    = [];
        $has_been_approved_once = [];
        for($i = 0; $i < count($T); $i++){
            $topics[$i]                         = $T[$i] -> pending_name;
            $topics_categories[$topics[$i]]     = Category::where('id', '=', $T[$i] -> category_id) -> first() -> approved_name;
            $topics_tags[$i]                    = Topic::where('approved_name', '=', $topics[$i]) -> orWhere('pending_name', '=', $topics[$i]) -> get() -> first() -> tags() -> get();
            $needs_approval[$i]                 = $T[$i] -> needs_approval;
            $is_approval_pending[$i]            = $T[$i] -> is_approval_pending;
            $has_been_approved_once[$i]         = $T[$i] -> approved_name ? true : false;
        }
        $C = Category::all();
        $categories = [];
        for($i = 0; $i < count($C); $i++){
            $categories[$i] = $C[$i] -> approved_name;
        }
        return view('creator_topic_list', compact(['topics', 'topics_categories', 'topics_tags', 'categories', 'needs_approval', 'is_approval_pending', 'has_been_approved_once']));
    }

    public function categoryListJSON(Request $request){
        $categories = Category::all();
        $topic = Topic::where('pending_name', '=', $request -> topic) -> orWhere('pending_name', '=', $request -> topic) -> get() -> first();
        $category = Category::where('id', '=', $topic -> category_id) -> get() -> first();
        $category_name = $category -> approved_name;
        return compact(['categories', 'category_name']);
    }

    public function registerCategory(Request $request){
        Validator::extend('new_category', function($field,$value,$parameters){
            $category = Category::where('approved_name', '=', $value) -> orWhere('pending_name', '=', $value) -> first();
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

        $user_id = session('user_id');
        $creator = User::where('id', '=', $user_id) -> get() -> first() -> creator;
        if ($validator->passes()) {
            $category = new Category([
                'user_id'       => $user_id,
                'creator_id'    => $creator -> id,
                'pending_name'  => $request -> category_name,
            ]);
            $category -> save();
            Storage::disk('local') -> makeDirectory('public/'.$category -> pending_name);
            return response()->json(['success'=>'OK.']);
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function registerTopic(Request $request){

        Validator::extend('new_topic', function($field,$value,$parameters){
            $topic = Topic::where('pending_name', '=', $value) -> orWhere('approved_name', '=', $value) -> first();
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
            $user_id = session('user_id');
            $creator = User::where('id', '=', $user_id) -> get() -> first() -> creator;
            $category = Category::where('approved_name', '=', $request->category_name)->first();
            $topic = new Topic([
                'user_id'               => $user_id,
                'creator_id'            => $creator -> id,
                'pending_name'          => $request -> topic_name,
                'category_id'           => $category->id,
            ]);
            $topic->save();
            $tags_id = [];
            if($request -> tags != null){
                for($i = 0; $i < count($request -> tags); $i++){
                    $tag = Tag::firstOrCreate(array(
                        'name'          => $request -> tags[$i],
                    ));
                    $tag -> save();
                    $tags_id[$tag -> id] = array('topic_id' => $topic -> id);
                }
            }
            $category_path = "";
            if($category -> needs_approval || $category -> is_approval_pending){
                $category_path = $category -> pending_name;
            }else{
                $category_path = $category -> approved_name;
            }
            Storage::disk('local')->makeDirectory('public/' . $category_path . '/' . $topic->pending_name);
            Storage::disk('local')->makeDirectory('public/' . $category_path . '/' . $topic->pending_name . '/Simulacion');
            Storage::disk('local')->makeDirectory('public/' . $category_path . '/' . $topic->pending_name . '/Simulacion/latest');
            Storage::disk('local')->makeDirectory('public/' . $category_path . '/' . $topic->pending_name . '/Simulacion/changes');
            Storage::disk('local')->makeDirectory('public/' . $category_path . '/' . $topic->pending_name . '/Teoria/latest');
            Storage::disk('local')->makeDirectory('public/' . $category_path . '/' . $topic->pending_name . '/Teoria/changes');
            Storage::disk('local')->makeDirectory('public/' . $category_path . '/' . $topic->pending_name . '/Cuestionario/latest');
            Storage::disk('local')->makeDirectory('public/' . $category_path . '/' . $topic->pending_name . '/Cuestionario/changes');
            $topic -> tags() -> sync($tags_id);
            return response()->json(['success'=>'OK.']);
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function deleteCategory(Request $request){
        $category = Category::where('pending_name', '=', $request -> category_name) -> orWhere('approved_name', '=', $request -> category_name) -> first();
        $category -> delete();
        Storage::disk('local') -> deleteDirectory('public/'.$category -> name);
        return response() -> json(['success' => 'OK']);
    }

    public function deleteTopic(Request $request){
        $topic = Topic::where('pending_name', '=', $request -> topic_name) -> first();
        $category = Category::where('id', '=', $topic->category_id) -> first();
        $topic -> delete();
        Storage::disk('local') -> deleteDirectory('public/'. $category -> name. '/'.$topic -> name);
        return response() -> json(['success' => 'OK']);
    }

    public function editCategory(Request $request){
        Validator::extend('new_category', function($field,$value,$parameters){
            $old = $parameters[0];
            $new = $parameters[1];
            $category = Category::where('pending_name', '=', $new) -> orWhere('approved_name', '=', $new) -> first();
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
            $category = Category::where('pending_name', '=', $request -> category_name)->first();
            $category->update([
                'pending_name' => $request->new_category_name,
                'needs_approval' => true
            ]);
            $category->save();
            if($old != $new)
                Storage::move('public/' . $request->category_name, 'public/' . $category->pending_name);
            return response()->json(['success'=>'OK.']);
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function editTopic(Request $request){
        $old_topic_name                 = $request -> topic_name;
        $new_topic_name                 = $request -> new_topic_name;
        $new_category_name              = $request -> new_category_name;
        Validator::extend('alpha_spaces', function($attribute, $value){
            return preg_match('/^[\pL\s]+$/u', $value);
        });
        Validator::extend('not_default', function($field, $value, $parameters){
            return $value != 'Selecciona la categoria';
        });
        Validator::extend('valid_new_topic', function($field, $value, $parameters, $validator){
            $new_topic_name = $parameters[0];
            $old_topic_name = $parameters[1];
            $topic = Topic::where('pending_name', '=',  $new_topic_name) -> orWhere('approved_name', '=', $new_topic_name)-> first();
            return $new_topic_name == $old_topic_name || $topic == null;
        });
        $messages = array(
            'required'              => 'El nombre del tema es requerido.',
            'not_default'           => 'Selecciona una categoria.',
            'alpha_spaces'          => 'Solo se permiten letras y espacios.',
            'valid_new_topic'       => 'El nombre del tema ya existe, elige otro.'
        );
        $validator = Validator::make($request->all(), [
            'new_topic_name' => "required|alpha_spaces|valid_new_topic:$new_topic_name,$old_topic_name",
            'new_category_name' => 'not_default',
        ], $messages);
        if ($validator->passes()) {
            $topic                          = Topic::where('pending_name','=', $old_topic_name) -> first();
            $old_category                   = Category::where('id', '=', $topic -> category_id) -> first();
            $new_category                   = Category::where('approved_name', '=', $new_category_name) -> first();
            $topic -> update([
                'category_id'   => $new_category -> id,
                'pending_name'  => $request -> new_topic_name,
                'needs_approval' => true,
            ]);
            $tags_id = [];
            $topic -> tags() -> detach();
            if($request -> tags != null){
                for($i = 0; $i < count($request -> tags); $i++){
                    $tag = Tag::firstOrCreate(array(
                        'name'          => $request -> tags[$i],
                    ));
                    $tag -> save();
                    $hasTag = DB::select('SELECT * FROM tag_topic WHERE topic_id = ? and tag_id = ?', [$topic -> id, $tag -> id]);
                    if(count($hasTag) == 0){
                        $topic -> tags() -> attach($tag -> id, [
                            'topic_id' => $topic -> id,
                        ]);
                    }
                }
            }
            if($new_category -> needs_approval || $new_category -> is_approval_pending){
                $new_category_path = $new_category -> pending_name;
            }else{
                $new_category_path = $new_category -> approved_name;
            }
            if($old_category -> needs_approval || $old_category -> is_approval_pending){
                $old_category_path = $old_category -> pending_name;
            }else{
                $old_category_path = $old_category -> approved_name;
            }
            if($old_topic_name != $new_topic_name && $old_category -> approved_name != $new_category -> approved_name)
                Storage::move('public/' . $old_category_path . '/' . $old_topic_name, 'public/' . $new_category_path . '/' . $topic-> pending_name);
            if($old_topic_name != $new_topic_name && $old_category -> approved_name == $new_category -> approved_name)
                Storage::move('public/' . $old_category_path . '/' . $old_topic_name, 'public/' . $old_category_path . '/' . $topic-> pending_name);
            if($old_topic_name == $new_topic_name && $old_category -> approved_name != $new_category -> approved_name)
                Storage::move('public/' . $old_category_path . '/' . $old_topic_name, 'public/' . $new_category_path . '/' . $old_topic_name);
            return response() -> json(['success' => 'OK']);
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function displayTopic($name){
        $topic_name             = $name;
        $urls                   = [];
        $topic                  = Topic::where('approved_name', '=', $topic_name) -> orWhere('pending_name', '=', $topic_name) -> first();
        if(!$topic){
            return view('not_found');
        }
        $R                      = $topic -> references() -> get();
        $references             = [];
        $references['T']        = false;
        $references['S']        = false;
        $references['C']        = false;
        $creation_type          = [];
        $needs_approval         = [];
        $is_approval_pending    = [];
        for($i = 0; $i < count($R); $i++){
            if($R[$i] -> type == 'C') {
                $references['C'] = true;
                $creation_type['C'] = $R[$i] -> uploaded_using_file ? 'file' : 'interface';
                $needs_approval['C'] = $R[$i] -> needs_approval;
                $is_approval_pending['C'] = $R[$i] -> is_approval_pending;
            }
            if($R[$i] -> type == 'T') {
                $references['T'] = true;
                $creation_type['T'] = $R[$i] -> uploaded_using_file ? 'file' : 'interface';
                $needs_approval['T'] = $R[$i] -> needs_approval;
                $is_approval_pending['T'] = $R[$i] -> is_approval_pending;
            }
            if($R[$i] -> type == 'S') {
                $references['S'] = true;
                $creation_type['S'] = $R[$i]->uploaded_using_file ? 'file' : 'interface';
                $needs_approval['S'] = $R[$i]->needs_approval;
                $is_approval_pending['S'] = $R[$i]->is_approval_pending;
            }
        }
        return view('topic', compact(['topic_name', 'references', 'creation_type', 'needs_approval', 'is_approval_pending']));
    }

    public function topicTheoryManager($name){
        $topic_name = $name;
        $topic                  = Topic::where('approved_name', '=', $topic_name) -> orWhere('pending_name', '=', $topic_name) -> first();
        if(!$topic){
            return view('not_found');
        }
        return view('theorymanager', compact('name'));
    }

    public function topicSimulationManager($name){
        $topic_name = $name;
        $topic                  = Topic::where('approved_name', '=', $topic_name) -> orWhere('pending_name', '=', $topic_name) -> first();
        if(!$topic){
            return view('not_found');
        }
        return view('simulationmanager', compact('name'));
    }

    public function topicQuestionnaireManager($name){
        $topic_name = $name;
        $topic                  = Topic::where('approved_name', '=', $topic_name) -> orWhere('pending_name', '=', $topic_name) -> first();
        if(!$topic){
            return view('not_found');
        }
        return view('questionnairemanager', compact('name'));
    }

    public function registerTheoryFile(Request $request){
        if ($request->hasFile('input_file')) {
            $topic = Topic::where('approved_name', '=', $request -> topic_name) -> orWhere('pending_name', '=', $request -> topic_name) -> first();
            $category   = Category::where('id', '=', $topic -> category_id) -> first();
            $name = $request -> file('input_file') -> getClientOriginalName();
            $file_size = $request -> file('input_file') -> getClientSize();
            $messages = array(
                'file_size'      => 'Elige un archivo XML que pese menos de 500KB',
            );
            Log::debug($request -> input_file -> getClientSize());
            // 1MB
            Validator::extend('file_size', function($attribute, $value){
                return $value -> getClientSize() / 1024.0 <= 500;
            });
            $array_validations = [];
            $array_validations['input_file'] = 'file_size';
            $validator = Validator::make($request->all(), $array_validations, $messages);
            if($validator->passes()) {
                $category_path = "";
                $topic_path = "";
                if ($category->needs_approval || $category->is_approval_pending) {
                    $category_path = $category->pending_name;
                } else {
                    $category_path = $category->approved_name;
                }
                if ($topic->needs_approval || $topic->is_approval_pending) {
                    $topic_path = $topic->pending_name;
                } else {
                    $topic_path = $topic->approved_name;
                }
                $destinationPath = public_path('storage/' . $category_path . '/' . $topic_path . '/Teoria/changes');
                $request->input_file->move($destinationPath, "teoria.xml");
                $reference = new Reference();
                $reference->type = 'T';
                $reference->pending_route = $destinationPath;
                $reference->needs_approval = true;
                $reference->is_approval_pending = false;
                $reference->category_id = $category->id;
                $reference->topic_id = $topic->id;
                $reference->uploaded_using_file = true;
                $reference->save();
                return redirect('creator/topic/' . $topic->pending_name) -> with('file_success_theory', ['Archivo guardado exitosamente.']);
            }else{
                return Redirect::back()->with('file_errors_theory', $validator -> errors() -> all());
            }
        }else{
            return Redirect::back()->with('file_errors_theory', ['Selecciona un archivo XML']);
        }
    }

    public function registerSimulationFile(Request $request){
        if ($request->hasFile('input_file')) {
            $topic = Topic::where('approved_name', '=', $request->topic_name)->orWhere('pending_name', '=', $request->topic_name)->first();
            $category = Category::where('id', '=', $topic->category_id)->first();
            $file = $request->file('input_file');
            $name = $request->file('input_file') -> getClientOriginalName();
            $file_size = $request->file('input_file')->getClientSize();
            $messages = array(
                'file_size' => 'Elige un archivo zip que pese como máximo 1MB',
            );
            // 1MB
            Validator::extend('file_size', function ($attribute, $value) {
                return $value -> getClientSize() / 1024.0 <= 1000;
            });
            $array_validations = [];
            $array_validations['input_file'] = 'file_size';
            $validator = Validator::make($request->all(), $array_validations, $messages);
            if ($validator->passes()){
                $category_path = "";
                $topic_path = "";
                if ($category->needs_approval || $category->is_approval_pending) {
                    $category_path = $category->pending_name;
                } else {
                    $category_path = $category->approved_name;
                }
                if ($topic->needs_approval || $topic->is_approval_pending) {
                    $topic_path = $topic->pending_name;
                } else {
                    $topic_path = $topic->approved_name;
                }
                $destinationPath = public_path('storage/' . $category_path . '/' . $topic_path . '/Simulacion/changes/');
                $request->input_file->move($destinationPath, 'archivo.zip');
                $zip = new ZipArchive();
                $zip_reference = $zip->open($destinationPath . 'archivo.zip');
                if ($zip_reference) {
                    $zip->extractTo($destinationPath);
                    $zip->close();
                    unlink($destinationPath . 'archivo.zip');
                    $reference = new Reference();
                    $reference->type = 'S';
                    $reference->pending_route = $destinationPath;
                    $reference->needs_approval = true;
                    $reference->is_approval_pending = false;
                    $reference->uploaded_using_file = true;
                    $reference->category_id = $category->id;
                    $reference->topic_id = $topic->id;
                    $reference->save();
                }
                return redirect('creator/topic/' . $topic->pending_name) -> with('file_success_simulation', ['Archivo guardado exitosamente.']);
            }else{
                return Redirect::back()->with('file_errors_simulation', $validator -> errors() -> all());
            }
        }else{
            return Redirect::back()->with('file_errors_simulation', ['Selecciona un archivo zip.']);
        }
    }

    public function registerQuestionnaireFile(Request $request){
        if ($request->hasFile('input_file')) {
            $file_size = $request->file('input_file')->getClientSize();
            $messages = array(
                'file_size' => 'Elige un archivo XML que pese como máximo 500KB.',
            );
            // 1MB
            Validator::extend('file_size', function ($attribute, $value) {
                return $value->getClientSize() / 1024.0 <= 500;
            });
            $array_validations = [];
            $array_validations['input_file'] = 'file_size';
            $validator = Validator::make($request->all(), $array_validations, $messages);
            if ($validator->passes()) {
                $topic = Topic::where('approved_name', '=', $request->topic_name)->orWhere('pending_name', '=', $request->topic_name)->first();
                $category = Category::where('id', '=', $topic->category_id)->first();
                $category_path = "";
                $topic_path = "";
                if ($category->needs_approval || $category->is_approval_pending) {
                    $category_path = $category->pending_name;
                } else {
                    $category_path = $category->approved_name;
                }
                if ($topic->needs_approval || $topic->is_approval_pending) {
                    $topic_path = $topic->pending_name;
                } else {
                    $topic_path = $topic->approved_name;
                }
                $destinationPath = public_path('storage/' . $category_path . '/' . $topic_path . '/Cuestionario/changes');
                $request->input_file->move($destinationPath, 'cuestionario.xml');
                $reference = new Reference();
                $reference->type = 'C';
                $reference->pending_route = $destinationPath;
                $reference->needs_approval = true;
                $reference->is_approval_pending = false;
                $reference->uploaded_using_file = true;
                $reference->category_id = $category->id;
                $reference->topic_id = $topic->id;
                $reference->save();
                return redirect('creator/topic/' . $topic->pending_name) -> with('file_success_questionnaire', ['Archivo guardado exitosamente.']);
            }else{
                return Redirect::back()->with('file_errors_questionnaire', $validator -> errors() -> all());
            }
        }else{
            return Redirect::back()->with('file_errors_questionnaire', ['Selecciona un archivo XML.']);
        }
    }

    public function editTopicTheoryFile(Request $request){
        if ($request->hasFile('input_file')) {
            $topic = Topic::where('approved_name', '=', $request -> topic_name) -> orWhere('pending_name', '=', $request -> topic_name) -> first();
            $category   = Category::where('id', '=', $topic -> category_id) -> first();
            $name = $request -> file('input_file') -> getClientOriginalName();
            $file_size = $request -> file('input_file') -> getClientSize();
            $messages = array(
                'file_size'      => 'Elige un archivo XML que pese menos de 500KB',
            );
            Log::debug($request -> input_file -> getClientSize());
            // 500 KB
            Validator::extend('file_size', function($attribute, $value){
                return strlen($value -> getClientSize()) / 1024.0 <= 500;
            });
            $array_validations = [];
            $array_validations['input_file'] = 'file_size';
            $validator = Validator::make($request->all(), $array_validations, $messages);
            if($validator->passes()) {
                $category_path = "";
                $topic_path = "";
                if ($category->needs_approval || $category->is_approval_pending) {
                    $category_path = $category->pending_name;
                } else {
                    $category_path = $category->approved_name;
                }
                if ($topic->needs_approval || $topic->is_approval_pending) {
                    $topic_path = $topic->pending_name;
                } else {
                    $topic_path = $topic->approved_name;
                }
                $destinationPath = public_path('storage/' . $category_path . '/' . $topic_path . '/Teoria/changes');
                File::cleanDirectory($destinationPath);
                $request->input_file->move($destinationPath, "teoria.xml");
                $reference = Reference::where('topic_id', '=', $topic->id)->where('type', '=', 'T')->first();
                $reference->needs_approval = true;
                $reference->is_approval_pending = false;
                $reference->save();
                return redirect('creator/topic/' . $topic->pending_name) -> with('file_success_theory', ['Archivo guardado exitosamente.']);
            }else{
                return Redirect::back()->with('file_errors_theory', $validator -> errors() -> all());
            }
        }else{
            return Redirect::back()->with('file_errors_theory', ['Selecciona un archivo XML']);
        }
    }

    public function editTopicSimulationFile(Request $request){
        if ($request->hasFile('input_file')) {
            $topic = Topic::where('approved_name', '=', $request -> topic_name) -> orWhere('pending_name', '=', $request -> topic_name) -> first();
            $category   = Category::where('id', '=', $topic -> category_id) -> first();
            $name = $request -> file('input_file') -> getClientOriginalName();
            $file_size = $request -> file('input_file') -> getClientSize();
            $messages = array(
                'file_size'      => 'Elige un archivo zip que pese máximo 1MB.',
            );
            Log::debug($request -> input_file -> getClientSize());
            // 500 KB
            Validator::extend('file_size', function($attribute, $value){
                return strlen($value -> getClientSize()) / 1024.0 <= 1000;
            });
            $array_validations = [];
            $array_validations['input_file'] = 'file_size';
            $validator = Validator::make($request->all(), $array_validations, $messages);
            if($validator->passes()) {
                $topic = Topic::where('approved_name', '=', $request->topic_name)->orWhere('pending_name', '=', $request->topic_name)->first();
                $category = Category::where('id', '=', $topic->category_id)->first();
                $file = $request->file('input_file');
                $name = $request->file('input_file')->getClientOriginalName();
                $category_path = "";
                $topic_path = "";
                if ($category->needs_approval || $category->is_approval_pending) {
                    $category_path = $category->pending_name;
                } else {
                    $category_path = $category->approved_name;
                }
                if ($topic->needs_approval || $topic->is_approval_pending) {
                    $topic_path = $topic->pending_name;
                } else {
                    $topic_path = $topic->approved_name;
                }
                $destinationPath = public_path('storage/' . $category_path . '/' . $topic_path . '/Simulacion/changes/');
                File::cleanDirectory($destinationPath);
                $request->input_file->move($destinationPath, 'archivo.zip');
                $zip = new ZipArchive();
                $zip_reference = $zip->open($destinationPath . 'archivo.zip');
                if ($zip_reference) {
                    $zip->extractTo($destinationPath);
                    $zip->close();
                    unlink($destinationPath . 'archivo.zip');
                    $reference = Reference::where('topic_id', '=', $topic->id)->where('type', '=', 'S')->first();
                    $reference->needs_approval = true;
                    $reference->is_approval_pending = false;
                    $reference->save();
                }
                return redirect('creator/topic/' . $topic->pending_name) -> with('file_success_simulation', ['Archivo guardado exitosamente.']);
            }else{
                return Redirect::back()->with('file_errors_simulation', $validator -> errors() -> all());
            }
        }else{
            return Redirect::back()->with('file_errors_simulation', ['Selecciona un archivo zip.']);
        }
    }

    public function editTopicQuestionnaireFile(Request $request){
        if ($request->hasFile('input_file')) {
            $file_size = $request->file('input_file')->getClientSize();
            $messages = array(
                'file_size' => 'Elige un archivo XML que pese como máximo 500KB.',
            );
            // 1MB
            Validator::extend('file_size', function ($attribute, $value) {
                return $value->getClientSize() / 1024.0 <= 500;
            });
            $array_validations = [];
            $array_validations['input_file'] = 'file_size';
            $validator = Validator::make($request->all(), $array_validations, $messages);
            if ($validator->passes()) {
                $topic = Topic::where('approved_name', '=', $request->topic_name)->orWhere('pending_name', '=', $request->topic_name)->first();
                $category = Category::where('id', '=', $topic->category_id)->first();
                $name = $request->file('input_file')->getClientOriginalName();
                $category_path = "";
                $topic_path = "";
                if ($category->needs_approval || $category->is_approval_pending) {
                    $category_path = $category->pending_name;
                } else {
                    $category_path = $category->approved_name;
                }
                if ($topic->needs_approval || $topic->is_approval_pending) {
                    $topic_path = $topic->pending_name;
                } else {
                    $topic_path = $topic->approved_name;
                }
                $destinationPath = public_path('storage/' . $category_path . '/' . $topic_path . '/Cuestionario/changes');
                File::cleanDirectory($destinationPath);
                $request->input_file->move($destinationPath, 'cuestionario.xml');
                $reference = Reference::where('topic_id', '=', $topic->id)->where('type', '=', 'C')->first();
                $reference->needs_approval = true;
                $reference->is_approval_pending = false;
                $reference->save();
                return redirect('creator/topic/' . $topic->pending_name) -> with('file_success_questionnaire', ['Archivo guardado exitosamente.']);
            }else{
                return Redirect::back()->with('file_errors_questionnaire', $validator -> errors() -> all());
            }
        }else{
            return Redirect::back()->with('file_errors_questionnaire', ['Selecciona un archivo XML']);
        }
    }

    public function editTopicTheoryManually(Request $request){
        $topic = Topic::where('pending_name', '=', $request -> topic_name) -> orWhere('approved_name', '=', $request -> topic_name) -> first();
        $category_id        = $topic -> category_id;
        $category           = Category::where('id', '=', $category_id) -> get() -> first();
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
        $path = 'public/'.$category_path.'/'.$topic_path.'/Teoria/changes/';
        $xmlFilePath = Storage::allFiles($path);
        $topic_name = $topic_path;
        return view('edit_topic_manually', compact(['xmlFilePath', 'topic_name']));
    }

    public function editTopicQuestionnaireManually(Request $request){
        $topic              = Topic::where('pending_name', '=', $request -> topic_name) -> orWhere('approved_name', '=', $request -> topic_name) -> first();
        $category           = Category::where('id', '=', $topic -> category_id) -> first();
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
        $topic_name = $topic_path;
        $category_name = $category_path;
        return view('edit_questionnaire_manually', compact('topic_name', 'category_name'));
    }

    public function updateTopicQuestionnaireManually(Request $request){
        $topic = Topic::where('pending_name', '=', $request -> topic_name) -> orWhere('approved_name', '=', $request -> topic_name) -> first();
        $category = Category::where('id', '=', $topic -> category_id) -> first();
        $category_path  = "";
        $topic_path     = "";
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
        $destinationPath = public_path('storage/'.$category_path.'/'.$topic_path.'/Cuestionario/changes/cuestionario.xml');
        Storage::disk('local')->put('public/'.$category_path.'/'.$topic_path.'/Cuestionario/changes/cuestionario.xml', $request -> xmlContent);
        $reference = Reference::where('topic_id', '=', $topic -> id) -> where('type', '=', 'C') -> first();
        $reference -> pending_route = $destinationPath;
        $reference -> needs_approval = true;
        $reference -> uploaded_using_file   = false;
        $reference -> save();
        return response() -> json(['success' => 'OK']);
    }

    public function registerTheoryManually(Request $request){
        $topic_name = $request -> topic_name;
        return view('register_theory_manually', compact('topic_name'));
    }

    public function registerQuestionnaireManually(Request $request){
        $topic_name = $request -> topic_name;
        $topic = Topic::where('pending_name', '=', $topic_name) ->orWhere('approved_name', '=', $topic_name) -> first();
        $category = Category::where('id', '=', $topic -> category_id) -> first();
        $category_name = $category -> name;
        return view('register_questionnaire_manually', compact(['topic_name', 'category_name']));
    }

    public function saveTheoryManually(Request $request){
        $topic = Topic::where('pending_name', '=', $request -> topic_name) -> orWhere('approved_name', '=', $request -> topic_name) -> first();
        $category = Category::where('id', '=', $topic -> category_id) -> first();
        $category_path  = "";
        $topic_path     = "";
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
        $destinationPath = public_path('storage/'.$category_path.'/'.$topic_path.'/Teoria/changes/teoria.xml');
        Storage::disk('local')->put('public/'.$category_path.'/'.$topic_path.'/Teoria/changes/teoria.xml', $request -> xmlContent);
        $reference = new Reference();
        $reference -> type = 'T';
        $reference -> pending_route = $destinationPath;
        $reference -> category_id = $category -> id;
        $reference -> topic_id = $topic -> id;
        $reference -> uploaded_using_file   = false;
        $reference -> save();
        return response() -> json(['success' => 'OK']);
    }

    public function updateTopicTheoryManually(Request $request){
        $topic = Topic::where('pending_name', '=', $request -> topic_name) -> orWhere('approved_name', '=', $request -> topic_name) -> first();
        $category = Category::where('id', '=', $topic -> category_id) -> first();
        $category_path  = "";
        $topic_path     = "";
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
        $destinationPath = public_path('storage/'.$category_path.'/'.$topic_path.'/Teoria/changes/teoria.xml');
        Storage::disk('local')->put('public/'.$category_path.'/'.$topic_path.'/Teoria/changes/teoria.xml', $request -> xmlContent);
        $reference = Reference::where('topic_id','=', $topic -> id) -> where('type', '=', 'T') -> first();
        $reference -> pending_route  = $destinationPath;
        $reference -> needs_approval = true;
        $reference -> is_approval_pending = false;
        $reference -> save();
        return response() -> json(['success' => 'OK']);
    }
    public function saveQuestionnaireManually(Request $request){
        $topic = Topic::where('pending_name', '=', $request -> topic_name) -> orWhere('approved_name', '=', $request -> topic_name) -> first();
        $category = Category::where('id', '=', $topic -> category_id) -> first();
        $category_path  = "";
        $topic_path     = "";
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
        $destinationPath = public_path('storage/'.$category_path.'/'.$topic_path.'/Cuestionario/changes/cuestionario.xml');
        Storage::disk('local')->put('public/'.$category_path.'/'.$topic_path.'/Cuestionario/changes/cuestionario.xml', $request -> xmlContent);
        $reference = new Reference();
        $reference -> type = 'C';
        $reference -> topic_id = $topic -> id;
        $reference -> category_id = $category -> id;
        $reference -> pending_route = $destinationPath;
        $reference -> needs_approval = true;
        $reference -> uploaded_using_file   = false;
        $reference -> save();
        return response() -> json(['success' => 'OK']);
    }

    public function submitCategoryReview(Request $request){
        $category_name                      = $request -> category_name;
        $category                           = Category::where('pending_name', '=', $category_name) -> first();
        $category -> is_approval_pending    = true;
        $category -> needs_approval         = false;
        $sender_id                          = session('user_id');
        $admin                              = Admin::where('id', '=', 1) -> first();
        $type                               = "";
        if($category -> approved_name == ''){
            $type = 'A';
        }else{
            $type = 'E';
        }
        $notification = new Notification([
            'message'       => 'Agregando',
            'sender_id'     => $sender_id,
            'recipient_id'  => $admin -> user_id,
            'type'          => $type,
            'category_id'   => $category -> id,
        ]);
        $category -> save();
        $notification -> save();
    }

    public function submitTopicReview(Request $request){
        $topic_name                         = $request -> topic_name;
        $topic                              = Topic::where('pending_name', '=', $topic_name) -> first();
        $topic -> is_approval_pending       = true;
        $topic -> needs_approval            = false;
        $sender_id                          = session('user_id');
        $admin                              = Admin::where('id', '=', 1) -> first();
        $type                               = "";
        if($topic -> approved_name == ''){
            $type = 'A';
        }else{
            $type = 'E';
        }
        $notification = new Notification([
            'message'       => 'Agregando',
            'sender_id'     => $sender_id,
            'recipient_id'  => $admin -> user_id,
            'type'          => $type,
            'topic_id'      => $topic -> id,
        ]);
        $topic -> save();
        $notification -> save();
    }

    public function submitTopicTheoryReview(Request $request){
        $topic_name     = $request -> topic_name;
        $sender_id                          = session('user_id');
        $admin                              = Admin::where('id', '=', 1) -> first();
        $topic                              = Topic::where('pending_name','=', $topic_name) -> orWhere('approved_name', '=', $topic_name) -> first();
        $reference                          = Reference::where('topic_id', '=', $topic -> id) -> where('type', '=', 'T') -> first();
        $reference -> is_approval_pending   = true;
        $reference -> needs_approval        = false;
        $action_type    = '';
        if($reference -> approved_route == ''){
            $action_type = 'A';
        }else{
            $action_type = 'E';
        }
        $notification = new Notification([
            'message'       => 'Agregando',
            'sender_id'     => $sender_id,
            'recipient_id'  => $admin -> user_id,
            'type'          => $action_type,
            'reference_id'  => $reference -> id,
        ]);
        $notification   -> save();
        $reference      -> save();
        return redirect()->to(redirect()->getUrlGenerator()->previous());
    }

    public function submitTopicSimulationReview(Request $request){
        $topic_name     = $request -> topic_name;
        $sender_id                          = session('user_id');
        $admin                              = Admin::where('id', '=', 1) -> first();
        $topic                              = Topic::where('pending_name','=', $topic_name) -> orWhere('approved_name', '=', $topic_name) -> first();
        $reference                          = Reference::where('topic_id', '=', $topic -> id) -> where('type', '=', 'S') -> first();
        $reference -> is_approval_pending   = true;
        $reference -> needs_approval        = false;
        $action_type    = '';
        if($reference -> approved_route == ''){
            $action_type = 'A';
        }else{
            $action_type = 'E';
        }
        $notification = new Notification([
            'message'       => 'Agregando',
            'sender_id'     => $sender_id,
            'recipient_id'  => $admin -> user_id,
            'type'          => $action_type,
            'reference_id'  => $reference -> id,
        ]);
        $notification   -> save();
        $reference      -> save();
        return redirect()->to(redirect()->getUrlGenerator()->previous());
    }

    public function submitTopicQuestionnaireReview(Request $request){
        $topic_name     = $request -> topic_name;
        $sender_id                          = session('user_id');
        $admin                              = Admin::where('id', '=', 1) -> first();
        $topic                              = Topic::where('pending_name','=', $topic_name) -> orWhere('approved_name', '=', $topic_name) -> first();
        $reference                          = Reference::where('topic_id', '=', $topic -> id) -> where('type', '=', 'C') -> first();
        $reference -> is_approval_pending   = true;
        $reference -> needs_approval        = false;
        $action_type    = '';
        if($reference -> approved_route == ''){
            $action_type = 'A';
        }else{
            $action_type = 'E';
        }
        $notification = new Notification([
            'message'       => 'Agregando',
            'sender_id'     => $sender_id,
            'recipient_id'  => $admin -> user_id,
            'type'          => $action_type,
            'reference_id'  => $reference -> id,
        ]);
        $notification   -> save();
        $reference      -> save();
        return redirect()->to(redirect()->getUrlGenerator()->previous());
    }

    public function viewNotification($id){
        $notification_id    = $id;
        $notification       = Notification::where('id', '=', $notification_id) -> first();
        $user_id = session('user_id');
        if(!$notification || $notification -> recipient_id != $user_id){
            return view('not_found');
        }
        $topic_name         = "";
        $category_name      = "";
        $reference          = Reference::where('id', '=', $notification -> reference_id) -> first();
        if($notification -> topic_id){
            $topic = Topic::where('id', '=', $notification -> topic_id) -> first();
            if($topic -> approved_name){
                $topic_name = $topic -> approved_name;
            }else{
                $topic_name = $topic -> pending_name;
            }
        }
        if($notification -> category_id){
            $category = Category::where('id', '=', $notification -> category_id) -> first();
            if($category -> approved_name){
                $category_name = $category -> approved_name;
            }else{
                $category_name = $category -> pending_name;
            }
        }
        $reference_type     = "";
        if($notification -> reference_id){
            $topic_name = Topic::where('id', '=', $reference -> topic_id) -> first() -> approved_name;
            $category_name = Category::where('id', '=', $reference -> category_id) -> first() -> approved_name;
            $reference_type = $reference -> type;
        }
        $sender             = User::where('id', '=', $notification -> sender_id) -> first();
        $notification -> seen = true;
        $notification -> save();
        return view('creator_view_notification', compact(['notification', 'sender', 'topic', 'category', 'reference', 'topic_name', 'category_name', 'reference_type']));
    }

    public function downloadTheoryFile(Request $request){
        $topic = Topic::where('pending_name', '=', $request -> topic_name) -> orWhere('approved_name', '=', $request -> topic_name) -> first();
        $category = Category::where('id', '=', $topic -> category_id) -> first();
        $category_path  = "";
        $topic_path     = "";
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
        $destinationPath = public_path('storage/'.$category_path.'/'.$topic_path.'/Teoria/changes/teoria.xml');
        return response() -> download($destinationPath, 'teoria.xml');
    }

    public function downloadQuestionnaireFile(Request $request){
        $topic = Topic::where('pending_name', '=', $request -> topic_name) -> orWhere('approved_name', '=', $request -> topic_name) -> first();
        $category = Category::where('id', '=', $topic -> category_id) -> first();
        $category_path  = "";
        $topic_path     = "";
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
        $destinationPath = public_path('storage/'.$category_path.'/'.$topic_path.'/Cuestionario/changes/cuestionario.xml');
        return response() -> download($destinationPath, 'cuestionario.xml');
    }
}
