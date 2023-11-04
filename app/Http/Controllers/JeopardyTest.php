<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Question;
use App\Models\OriginalQuestion;


class JeopardyTest extends MyController {

    public function index() {
        return view('pages.jeopardy-test');
    }

    public function get_questions() {
        $questions = OriginalQuestion::inRandomOrder()->take(50)->get();
        return response()->json(['code'=>200, 'questions'=>$questions], 200);
    }
}