<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OriginalQuestion;
use App\Models\Category;
use App\Models\Question;
use App\Models\UserAnswerHeader;
use App\Models\UserAnswer;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends MyController {

    public function index() {
        // update logest streak_days
        if( $this->streak_days > $this->user->lognest_streak_days ) {
            $this->user->lognest_streak_days = $this->streak_days;
            $this->user->lognest_streak_started_at = $this->streak_started_date;
            $this->user->lognest_streak_ended_at = $this->streak_end_date;
            $this->user->save();
        }

        // get today test result
        $today_last_test = UserAnswerHeader::where("user_id", $this->user->id)->whereNotNull('ended_at')->where('ended_at', '>', date("Y-m-d 00:00:00"))->orderBy('ended_at', 'DESC')->first();

        // get rank
        $top_users_count = User::where("lognest_streak_days", ">", $this->user->lognest_streak_days)->count();

        return view('pages.dashboard', [
            'streak_started_date' => $this->streak_started_date,
            'streak_end_date' => $this->streak_end_date,
            'rank' => $top_users_count + 1,
            'today_last_test' => $today_last_test
        ])->with('streak_days', $this->streak_days);
    }

    public function get_daily_review() {
        // get last 6 months data
        $months_ago = 3;
        $test_histories = UserAnswerHeader::where("user_id", $this->user->id)->whereNotNull('ended_at')->where('ended_at', '>', (new \Carbon\Carbon)->submonths($months_ago))->select(DB::raw('DATE(ended_at) AS date'), DB::raw('COUNT(id) AS test_count'))->groupBy('date')->get();

        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d", strtotime( $to_date . '-' . $months_ago . ' months'));
        return response()->json([
            'code' => 200, 
            'histories' => $test_histories,
            'from_date' => $from_date,
            'to_date' => $to_date
        ], 200);
    }
}