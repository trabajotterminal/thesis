<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class Index extends Controller{

    public function index(){
        $ranked_users = DB::select('
          SELECT U.username AS username, U.profile_picture as profile_picture, G.name as group_name, Sc.name as school_name FROM Groups G, Schools Sc, Users U JOIN Students S ON U.id=S.user_id JOIN Marks M ON M.student_id=S.id GROUP BY M.user_id ORDER BY AVG(M.Points) DESC 
        ');
        $non_ranked_users = DB::select('
          SELECT U.username as username, U.profile_picture as profile_picture, S.name as school_name, G.name as group_name from users U, Groups G, Schools S where U.id IN (SELECT user_id FROM students SS WHERE NOT EXISTS (SELECT user_id FROM marks where student_id = SS.id )) and S.id = (select school_id from students where user_id = U.id) and G.id = (select group_id from students where user_id = U.id)
        ');
        return view('index', compact(['ranked_users', 'non_ranked_users']));
    }
}
