<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Category;
use App\Models\Question;
use App\Models\OriginalQuestion;
use App\Models\UserAnswer;
use App\Models\UserAnswerHeader;
use App\Models\FeatureTaskHeader;
use App\Models\FeatureTaskQuestion;


use Share;


class JeopardyTestController extends MyController {

    public function index() {
        // calculate remain test count if user have subscribed with Monthly
        $tested_count = 0;
        if($this->user->subscription_status == 1 && $this->user->subscription_plan == "Monthly") {
            $subscribed_date = date('d', strtotime($this->user->subscribed_at));

            $today_date = date('d');
            $today_month = date('Y-m');

            $compare_date = $today_month . '-' . $subscribed_date;
            if($today_date < $subscribed_date) {
                // get 1 month before
                $compare_date = date('Y-m-d', strtotime("-1 month", strtotime($compare_date))); 
            }

            // get count
            $tested_count = count(UserAnswerHeader::where('user_id', $this->user->id)->whereNotNull('ended_at')->where('ended_at', '>=', $compare_date)->get());
        }

        $free_count = $this->user->referral_user_count - $this->user->used_referrals_for_test;

        $featured_tests = FeatureTaskHeader::where('is_delete', 0)->orderBy('ranking')->get();
        

        $max_scores = UserAnswerHeader::getMaxFeaturedTestScores($this->user->id);

        // insert max score
        if(count($featured_tests) > 0) {
            for($index = 0; $index < count($featured_tests); $index++) {
                foreach($max_scores as $max_score) {
                    if($featured_tests[$index]->id == $max_score->featured_test_id) {
                        $featured_tests[$index]['max_score'] = $max_score->score;
                        $featured_tests[$index]['number_of_questions'] = $max_score->number_of_questions;
                        $featured_tests[$index]['max_scored_date'] = $max_score->ended_at;
                        break;
                    }
                }
            }
        }
        

        return view('pages.jeopardy-test', [
            'tested_count' => $tested_count,
            'free_count' => $free_count,
            'featured_tests' => $featured_tests
        ])->with('streak_days', $this->streak_days);
    }

    public function get_questions($count) {
        // check user have reach out the Mothly limit
        if($this->user->subscription_status == 1 && $this->user->subscription_plan == "Monthly") {
            $subscribed_date = date('d', strtotime($this->user->subscribed_at));

            $today_date = date('d');
            $today_month = date('Y-m');

            $compare_date = $today_month . '-' . $subscribed_date;
            if($today_date < $subscribed_date) {
                // get 1 month before
                $compare_date = date('Y-m-d', strtotime("-1 month", strtotime($compare_date))); 
            }

            // get count
            $tested_count = count(UserAnswerHeader::where('user_id', $this->user->id)->whereNotNull('ended_at')->where('ended_at', '>=', $compare_date)->get());
            if($tested_count >= env('MONTHLY_PLAN_TEST_COUNT')) {
                return response()->json(['code'=>400, 'message'=>'Exceeded monthly limit.'], 200);
            }
        }

        $questions = OriginalQuestion::inRandomOrder()->take($count)->get();
        // $questions = OriginalQuestion::where('id', "<", 6)->take(5)->get();

        $test_type = 0;         // general test
        if($this->user->subscription_status == 0) {
            $test_type = 1;     // Trial test
        }

        // create Header
        $header = UserAnswerHeader::create([
            'user_id' => $this->user->id,
            'number_of_questions' => $count,
            'test_type' => $test_type,
            'started_at' => date("Y-m-d H:i:s")
        ]);

        $questions->makeHidden(['value', 'answer']);
       
        // remove hyperlink from the question
        for($index = 0; $index < count($questions); $index++) {
            $questions[$index]->question = strip_tags($questions[$index]->question);
        }


        return response()->json(['code'=>200, 'questions'=>$questions, 'header_id' => $header->id], 200);
    }

    public function get_feature_test($test_id) {
        if($this->user->subscription_status == 0)
            return response()->json(['code'=>400, 'message'=>'This test is for Subscribers Only!'], 200);

        // check user have reach out the Mothly limit
        if($this->user->subscription_status == 1 && $this->user->subscription_plan == "Monthly") {
            $subscribed_date = date('d', strtotime($this->user->subscribed_at));

            $today_date = date('d');
            $today_month = date('Y-m');

            $compare_date = $today_month . '-' . $subscribed_date;
            if($today_date < $subscribed_date) {
                // get 1 month before
                $compare_date = date('Y-m-d', strtotime("-1 month", strtotime($compare_date))); 
            }

            // get count
            $tested_count = count(UserAnswerHeader::where('user_id', $this->user->id)->whereNotNull('ended_at')->where('ended_at', '>=', $compare_date)->get());
            if($tested_count >= env('MONTHLY_PLAN_TEST_COUNT')) {
                return response()->json(['code'=>400, 'message'=>'Exceeded monthly limit.'], 200);
            }
        }

        $questions = FeatureTaskQuestion::where('header_id', $test_id)->get();
       

        $test_type = 2;         //Featured Test 
        
        // create Header
        $header = UserAnswerHeader::create([
            'user_id' => $this->user->id,
            'number_of_questions' => count($questions),
            'test_type' => $test_type,
            'featured_test_id' => $test_id,
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

        if($this->user->subscription_status == "0") {
            if($this->user->is_trial_used == "0") {
                $this->user->is_trial_used = 1;
                $this->user->last_tested_at = date("Y-m-d H:i:s");
                $this->user->remain_trail_test_times = $this->user->remain_trail_test_times - 1;
                $this->user->save();
            } else {
                $free_count = $this->user->referral_user_count - $this->user->used_referrals_for_test;
                if($free_count > 0) {
                    $this->user->used_referrals_for_test = $this->user->used_referrals_for_test + 1;
                    $this->user->save();
                }
            }
        } else if($this->user->subscription_status == "1" && $this->user->subscription_plan == "Monthly") {
            $subscribed_date = date('d', strtotime($this->user->subscribed_at));

            $today_date = date('d');
            $today_month = date('Y-m');

            $compare_date = $today_month . '-' . $subscribed_date;
            if($today_date < $subscribed_date) {
                // get 1 month before
                $compare_date = date('Y-m-d', strtotime("-1 month", strtotime($compare_date))); 
            }

            // get count
            $tested_count = count(UserAnswerHeader::where('user_id', $this->user->id)->whereNotNull('ended_at')->where('ended_at', '>=', $compare_date)->get());
            $free_count = $this->user->referral_user_count - $this->user->used_referrals_for_test;

            if($tested_count >= env('MONTHLY_PLAN_TEST_COUNT')) {
                if($tested_count < env('MONTHLY_PLAN_TEST_COUNT') + $free_count) {
                    $this->user->used_referrals_for_test = $this->user->used_referrals_for_test + 1;
                    $this->user->save();
                }
            }
        }

        // get Header
        $header = UserAnswerHeader::where('id', $header_id)->first();

        $correct_count = 0;
        foreach($answers as $my_answer) {
            // check this question is featured question
            if($header->test_type == 2) {
                $question = FeatureTaskQuestion::where('id', $my_answer->id)->first();
            } else {
                $question = OriginalQuestion::where('id', $my_answer->id)->first();
            }
           
            $answer = UserAnswer::create([
                'header_id' => $header->id,
                'user_id' => $this->user->id,
                'question_id' => $question->id,
                'question' => $question->question,
                'answer' => $question->answer,
                'value' => $question->value,
                'user_answer' => strip_tags($my_answer->user_answer),
                'answer_time' => $my_answer->answer_time
            ]);

            if(
                strtolower(trim($question->answer)) == strtolower(trim($my_answer->user_answer)) || 
                strtolower(trim($question->answer)) == strtolower(trim("a " . $my_answer->user_answer)) ||
                strtolower(trim($question->answer)) == strtolower(trim("the " . $my_answer->user_answer))
            ) {
                $correct_count++;
                $answer->is_correct = 1;
                $answer->save();
            }
        }

        $header->score = $correct_count;
        $header->ended_at = date("Y-m-d H:i:s");
        $header->save();

        $my_answers = UserAnswer::where('header_id', $header->id)->get();
        $diff = $header->get_test_time();
        $test_time = $diff['formated'];

        $this->streak_days = $this->recalculateCurrentStreak($this->user->id);

        // $shareComponent = Share::page (
        //     env('APP_URL') . "share-myscore/" . $header->id,
        //     'My J!Study Score Today:',
        // )
        // ->facebook()
        // ->twitter()->getRawLinks();

        // generate Share string
        $ref_str = $this->encryptAndTruncate(strval($this->user->id), env('SHORT_ENCRYPT_KEY'));

        $score_part = '';
        foreach($my_answers as $answer) {
            if($answer->is_correct == 1) {
                $score_part .= '✅';
            } else {
                $score_part .= '❌';
            }
        }
        $score_part .= " " . $correct_count . '/' . count($my_answers);

        $tip_part = "\nTIP: If the auto-grader mistakenly marked a correct response as incorrect, you can click “INCORRECT” to toggle the response to “CORRECT”.";

        $time_part =  "\n⏳ " . $test_time;
        $streak_part = "\n⚡ " . $this->streak_days . " Day Streak";
        // $link_part = " See how you do: https://jstudy.app/ref/" . $ref_str;
        $link_part = "\nSee how you do: ";

        $shareComponent = Share::page (
            env('APP_URL') . 'ref/' . $ref_str,
            'My J!Study Score Today: ' . $score_part . $tip_part . $time_part . $streak_part . $link_part
        )
        ->facebook()
        ->twitter()->getRawLinks();

        return response()->json([
            'code'=>200, 
            'score'=>$correct_count,
            'my_answers' => $my_answers,
            'answer_summuary' => $header,
            'test_time' => $test_time,
            'streak_days' => $this->streak_days,
            'shareComponent' => $shareComponent
        ], 200);
    }


    public function view_detail($header_id) {
        $header = UserAnswerHeader::where('id', $header_id)->first();
        $details = UserAnswer::where('user_id', $this->user->id)->where('header_id', $header_id)->get();

        return view('pages.view-detail', [
            'header' => $header,
            'details' => $details
        ])->with('streak_days', $this->streak_days);
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