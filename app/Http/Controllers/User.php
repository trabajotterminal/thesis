<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;
use \App\School;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Log;
class User extends Controller{
    public function profile(){
        $institution_list   = School::all();
        $user_id = session('user_id');
        $user = \App\User::where('id', '=', $user_id) -> first();
        return view('user_profile', compact(['institution_list', 'user']));
    }

    public function getGroupsBySchool(Request $request){
        $school = School::where('name', '=', $request -> school_name) -> first();
        $groups = [];
        if($school)
            $groups =  Group::where('school_id', '=', $school -> id) -> get();
        return compact(['groups']);
    }

    public function updateInfo(Request $request){
        $messages = array(
            'new_password.required'     => 'Introduce una nueva contraseña',
            'password.required'         => 'Introduce tu contraseña actual',
            'new_club.required'         => 'Introduce el nombre de tu club',
            'new_institution.required'  => 'Introduce el nombre de tu institución',
            'alpha_spaces'              => 'Solo se permiten letras y espacios',
            'match_old_password'        => 'La contraseña actual es incorrecta',
            'new_institution'           => 'El nombre de la institución ya existe',
            'new_club'                  => 'El nombre del club ya existe',
        );
        $validator = "";
        $array_validations = [];
        $user_id = $request -> user_id;

        Validator::extend('alpha_spaces', function($attribute, $value){
            return preg_match('/^[\pL\s]+$/u', $value);
        });

        Validator::extend('match_old_password', function($field, $value, $parameters){
            $user_id = $parameters[0];
            $user = \App\User::where('id', '=', $user_id) -> first();
            return Hash::check($value, $user -> password);
        });

        Validator::extend('new_institution', function($field, $value, $parameters){
            $school = School::where('name', '=', $value) -> first();
            return $school == null;
        });

        Validator::extend('new_club', function($field, $value, $parameters){
            $group = Group::where('name', '=', $value) -> first();
            return $group == null;
        });

        if($request -> institution == 'Otra'){
            $array_validations['new_institution'] = 'required|alpha_spaces|new_institution';
        }

        if($request -> club == 'Otro'){
            $array_validations['new_club'] = 'required|alpha_spaces|new_club';
        }

        if($request -> password || $request -> new_password) {
            $array_validations['password'] = 'required|match_old_password:'.$user_id;
            $array_validations['new_password'] = 'required';
        }

        $validator = Validator::make($request->all(), $array_validations, $messages);

        if($validator->passes()){
            $user = \App\User::where('id', '=', $user_id) -> first();
            $student = $user -> student() -> first();
            $user -> profile_picture = $request -> profile_picture;

            if($request -> password){
                $user -> password = bcrypt($request -> new_password);
            }

            $school = "";
            if($request -> institution == 'Otra'){
                $school = new School([
                    'name' => $request -> new_institution,
                ]);
                $school -> save();
                $student -> school_id = $school -> id;
            }else{
                $school = School::where('name', '=', $request -> institution) -> first();
                $student -> school_id = $school -> id;
            }

            if($request -> club == 'Otro'){
                $club = new Group([
                    'name' => $request -> new_club,
                    'school_id' => $school -> id,
                ]);
                $club   -> save();
                $student -> group_id = $club -> id;
            }else{
                $club = Group::where('name', '=', $request -> club) -> first();
                $student -> group_id = $club -> id;
            }
            $user -> save();
            $student -> save();
            return response()->json(['success' => 'OK.']);
        }
        return response()->json(['error' => $validator->errors()->all()]);
    }

    public function getInfoJson(Request $request){
        $user_id    = $request -> user_id;
        $student    = \App\User::where('id', '=', $user_id) -> first() -> student;
        $school = "";
        $group  = "";
        if($student -> school_id)
            $school     = School::where('id', '=', $student -> school_id) -> first() -> name;
        if($student -> group_id)
            $group      = Group::where('id', '=', $student -> group_id) -> first() -> name;
        return compact(['school', 'group']);
    }

    public function getSchoolsJson(Request $request){
        $schools = School::all();
        return compact(['schools']);
    }
}
