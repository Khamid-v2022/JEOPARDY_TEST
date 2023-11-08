<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Question;
use App\Models\OriginalQuestion;
use App\Models\UserAnswer;
use App\Models\UserAnswerHeader;


class JeopardyTestController extends MyController {

    public function index() {
        return view('pages.jeopardy-test');
    }

    public function get_questions() {
        $questions = OriginalQuestion::inRandomOrder()->take(50)->get();
        // $questions = OriginalQuestion::where('id', "<", 6)->take(50)->get();

        $is_trial_test = 0;
        if($this->user->subscription_status == 0) {
            $is_trial_test = 1;
        }

        // create Header
        $header = UserAnswerHeader::create([
            'user_id' => $this->user->id,
            'is_trial_test' => $is_trial_test,
            'started_at' => date("Y-m-d H:i:s")
        ]);

        $questions->makeHidden(['value', 'answer']);
       
        // remove hyperlink from the question
        for($index = 0; $index < count($questions); $index++) {
            $questions[$index]->question = strip_tags($questions[$index]->question);
        }

        return response()->json(['code'=>200, 'questions'=>$questions, 'header_id' => $header->id], 200);
    }

    public function submit_response(Request $request) {
        $answers = json_decode($request->data);
        $header_id = $request->header_id;

        $is_trial_test = 0;
        if($this->user->subscription_status == 0) {
            $this->user->is_trial_used = 1;
            $this->user->save();

            $is_trial_test = 1;
        }

        // get Header
        $header = UserAnswerHeader::where('id', $header_id)->first();

        $correct_count = 0;
        foreach($answers as $answer) {
            $question = OriginalQuestion::where('id', $answer->id)->first();
            $answer = UserAnswer::create([
                'header_id' => $header->id,
                'user_id' => $this->user->id,
                'question_id' => $question->id,
                'user_answer' => $answer->user_answer,
                'answer_time' => $answer->answer_time
            ]);

            if(strtolower(trim($question->answer)) == strtolower(trim($answer->user_answer))){
                $correct_count++;
                $answer->is_correct = 1;
                $answer->save();
            }
        }

        $header->score = $correct_count;
        $header->ended_at = date("Y-m-d H:i:s");
        $header->save();


        return response()->json(['code'=>200, 'score'=>$correct_count], 200);
    }

    public function view_detail($header_id) {
        $header = UserAnswerHeader::where('id', $header_id)->first();
        $details = UserAnswer::where('user_id', $this->user->id)->where('header_id', $header_id)->get();

        return view('pages.view-detail', [
            'header' => $header,
            'details' => $details
        ]);
    }

    public function fix_answer(Request $request) {
        $answer = UserAnswer::where('id', $request->detail_id)->first();
        $answer->is_correct = $request->is_correct;
        $answer->save();

        // recalculate the score
        $header = UserAnswerHeader::where('id', $answer->header_id)->first();
        if($request->is_correct == 1){
            $header->score++;
        } else {
            $header->score--;
        }
        $header->save();

        return response()->json(['code'=>200, 'message'=>$answer], 200);
    }
}