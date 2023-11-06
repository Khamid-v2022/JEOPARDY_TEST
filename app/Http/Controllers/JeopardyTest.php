<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Question;
use App\Models\OriginalQuestion;
use App\Models\UserAnswer;
use App\Models\UserAnswerHeader;


class JeopardyTest extends MyController {

    public function index() {
        return view('pages.jeopardy-test');
    }

    public function get_questions() {
        // $questions = OriginalQuestion::inRandomOrder()->take(50)->get();
        $questions = OriginalQuestion::where('id', "<", 5)->take(50)->get();
        $questions->makeHidden(['value', 'answer']);
        return response()->json(['code'=>200, 'questions'=>$questions], 200);
    }

    public function submit_response(Request $request) {
        $answers = json_decode($request->data);

        $is_trial_test = 0;
        if($this->user->subscription_status == 0) {
            $this->user->is_trial_used = 1;
            $this->user->save();

            $is_trial_test = 1;
        }

        // create Header
        $header = UserAnswerHeader::create([
            'user_id' => $this->user->id,
            'is_trial_test' => $is_trial_test
        ]);

        $correct_count = 0;
        foreach($answers as $answer) {
            $question = OriginalQuestion::where('id', $answer->id)->first();
            $answer = UserAnswer::create([
                'header_id' => $header->id,
                'user_id' => $this->user->id,
                'question_id' => $question->id,
                'user_answer' => $answer->user_answer
            ]);

            if(strtolower(trim($question->answer)) == strtolower(trim($answer->user_answer))){
                $correct_count++;
                $answer->is_correct = 1;
                $answer->save();
            }
        }

        $header->score = $correct_count;
        $header->save();


        return response()->json(['code'=>200, 'score'=>$correct_count], 200);
    }
}