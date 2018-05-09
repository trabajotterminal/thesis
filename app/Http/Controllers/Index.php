<?php

namespace App\Http\Controllers;

use App\Topic;
use App\Category;
use App\Tag;
use Illuminate\Http\Request;
use DB;
use Log;

class Index extends Controller{

    public function index(){
        $ranked_users = DB::select('
            SELECT U.username AS username, U.profile_picture as profile_picture, G.name as group_name, Sc.name as school_name FROM schools Sc
            JOIN groups G ON G.school_id=Sc.id
            JOIN students S ON S.group_id=G.id
            JOIN users U ON U.id=S.user_id 
            JOIN marks M ON M.student_id=S.id 
            GROUP BY M.user_id ORDER BY AVG(M.Points) DESC 
        ');
        $non_ranked_users = DB::select('
          SELECT U.username as username, U.profile_picture as profile_picture, S.name as school_name, G.name as group_name from users U, groups G, schools S where U.id IN (SELECT user_id FROM students SS WHERE NOT EXISTS (SELECT user_id FROM marks where student_id = SS.id )) and S.id = (select school_id from students where user_id = U.id) and G.id = (select group_id from students where user_id = U.id)
        ');
        return view('index', compact(['ranked_users', 'non_ranked_users']));
    }

    public function longestCommonSubstring($X, $Y){
        $dp = [];
        $result = 0;
        $ans    = "";
        $M = strlen($X);
        $N = strlen($Y);
        $row = 0;
        $col = 0;
        for ($i = 0; $i <= $M; $i++){
            for ($j = 0; $j <= $N; $j++){
                if ($i == 0 || $j == 0) {
                    $dp[$i][$j] = 0;
                }
                else{
                    if($X[$i - 1] == $Y[$j - 1]){
                        $dp[$i][$j] = $dp[$i - 1][$j - 1] + 1;
                        if($dp[$i][$j] >= $result) {
                            $result = $dp[$i][$j];
                            $row = $i;
                            $col = $j;
                        }
                    }
                    else
                        $dp[$i][$j] = 0;
                }
            }
        }
        if($result != 0){
            while ($dp[$row][$col] != 0) {
                $ans.= $X[$row - 1];
                $row--;
                $col--;
            }
            $ans = strrev($ans);
        }
        return $ans;
    }

    public function searchEngine(Request $request){
        $input          = $request -> input_search;
        if(!$input){
            return redirect('/');
        }
        $chars = array(
            chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
            chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
            chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
            chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
            chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
            chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
            chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
            chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
            chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
            chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
            chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
            chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
            chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
            chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
            chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
            chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
            chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
            chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
            chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
            chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
            chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
            chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
            chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
            chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
            chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
            chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
            chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
            chr(195).chr(191) => 'y',
            chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
            chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
            chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
            chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
            chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
            chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
            chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
            chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
            chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
            chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
            chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
            chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
            chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
            chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
            chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
            chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
            chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
            chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
            chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
            chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
            chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
            chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
            chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
            chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
            chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
            chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
            chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
            chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
            chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
            chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
            chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
            chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
            chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
            chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
            chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
            chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
            chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
            chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
            chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
            chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
            chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
            chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
            chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
            chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
            chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
            chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
            chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
            chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
            chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
            chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
            chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
            chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
            chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
            chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
            chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
            chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
            chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
            chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
            chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
            chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
            chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
            chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
            chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
            chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
        );
        $mapped_string  = [];
        $dictionary     = [];
        $topics         = Topic::all();
        for($i = 0; $i < count($topics); $i++) {
            $filtered_string = strtr($topics[$i]->approved_name, $chars);
            $mapped_string[$filtered_string] = $topics[$i]->approved_name;
            array_push($dictionary, $filtered_string);
        }
        $categories         = Category::all();
        for($i = 0; $i < count($categories); $i++) {
            $filtered_string = strtr($categories[$i]->approved_name, $chars);
            $mapped_string[$filtered_string] = $categories[$i]->approved_name;
            array_push($dictionary, $filtered_string);
        }
        $tags           = Tag::all();
        for($i = 0; $i < count($tags); $i++) {
            $filtered_string = strtr($tags[$i]-> name, $chars);
            $mapped_string[$filtered_string] = $tags[$i] -> name;
            array_push($dictionary, $filtered_string);
        }
        $predicted_word = "";
        $best_length    = -1;
        $filtered_input = strtr($input, $chars);
        $mapped_string[$filtered_input] = $input;
        for($i = 0; $i < count($dictionary); $i++){
            $aux = $this->longestCommonSubstring($dictionary[$i], $filtered_input);
            if(strlen($aux) >= $best_length){
                $best_length = strlen($aux);
                $predicted_word = $dictionary[$i];
            }
        }
        $should_display_predicted_word = false;
        $character_count    = strlen($predicted_word);
        $percentage         = $best_length && strlen($predicted_word) ? $best_length * 100.0 / strlen($predicted_word) : 0;
        $should_display_predicted_word = $percentage >= 60 && $best_length >= 3 ? true : false;
        $search_results = DB::select("
          SELECT DISTINCT t.approved_name as topic_name from topics t 
          where t.approved_name like ? 
          OR t.approved_name IN (SELECT approved_name from topics t JOIN tag_topic tt ON t.id=tt.topic_id 
          RIGHT JOIN tags tg ON tt.tag_id=tg.id where tg.name like ?) OR t.approved_name IN (SELECT t.approved_name as topic_name 
          from topics t join categories c on t.category_id=c.id where c.approved_name like ?) order by t.approved_name asc;", ['%'.$request -> input_search.'%', '%'.$request -> input_search.'%', '%'.$request -> input_search.'%']);
        $predicted_word = $predicted_word ? $mapped_string[$predicted_word] : "";
        $references = [];
        for($i = 0; $i < count($search_results); $i++){
            $topic = Topic::where('approved_name', '=', $search_results[$i] -> topic_name) -> first();
            $references[$i] = $topic -> references() -> where('approved_route', '!=', '') -> get();
        }
        return view('search_results', compact(['search_results', 'input', 'predicted_word', 'should_display_predicted_word', 'references']));
    }
}
