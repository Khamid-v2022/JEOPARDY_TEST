<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OriginalQuestion;
use App\Models\Category;
use App\Models\Question;
use App\Models\UserAnswerHeader;
use App\Models\UserAnswer;

use Illuminate\Support\Facades\DB;

class DashboardController extends MyController {

    public function index() {
        $my_tests = UserAnswerHeader::where('user_id', $this->user->id)->whereNotNull('ended_at')->orderBy('created_at', 'DESC')->get();
        if(count($my_tests) > 0) {
            for($index = 0; $index < count($my_tests); $index++) {
                $diff = $my_tests[$index]->get_test_time();
                $my_tests[$index]['progress_time'] = $diff['formated'];
                $my_tests[$index]['progress_time_second'] = $diff['second'];
            }
        }
        
        return view('pages.dashboard', [
            'history' => $my_tests
        ]);
    }

    public function delete_test_record($id) {
        UserAnswer::where('header_id', $id)->delete();
        UserAnswerHeader::where('id', $id)->delete();
        
        return response()->json(['code'=>200, 'message'=>'success'], 200);
    }

    public function get_scores_for_chart() {
        // get last 7 days
        $results =  UserAnswerHeader::where('user_id', $this->user->id)->whereNotNull('ended_at')
        ->select(DB::raw('DATE(ended_at) AS date'), DB::raw('SUM(score) AS score'), DB::raw('SUM(number_of_questions) AS number_of_questions'))->groupBy('date')
        ->orderBy('date', 'DESC')->take(7)->get();
        
        return response()->json(['code'=>200, 'scores'=>$results], 200);
    }

}