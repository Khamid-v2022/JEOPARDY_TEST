<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserAnswerHeader;


class MyController extends Controller {
    protected $user;
    protected $streak_days = 0;
    protected $streak_started_date = NULL;
    protected $streak_end_date = NULL;

    public function __construct() {
        parent::__construct();

        $this->middleware(function($request, $next) {
            $this->user = Auth::user();

            // get streak days
            $test_histories = UserAnswerHeader::where("user_id", $this->user->id)->whereNotNull('ended_at')->get();
            if(count($test_histories) > 0) {
                
                $yesteday = date('Y-m-d', strtotime("-1 days"));
                $compare_date = $yesteday;

                $today = date("Y-m-d");
                $this->streak_started_date = $today;
                
                $this->streak_days = 0;
                while(1) {
                    $flag = 0;
                    foreach($test_histories as $history) {
                        if(date('Y-m-d', strtotime($history->ended_at)) == $compare_date) {
                            $flag = 1;
                            break;
                        }
                    }

                    if($flag == 1) {
                        $this->streak_days++;
                        $this->streak_started_date = $compare_date;
                        
                        if(!$this->streak_end_date)
                            $this->streak_end_date = $compare_date;

                        $compare_date = date('Y-m-d', strtotime($compare_date . ' -1 day' ) );
                    } else {
                        break;
                    }
                }

                // check today test as well
                foreach($test_histories as $history) {
                    if(date('Y-m-d', strtotime($history->ended_at)) == $today) {
                        $this->streak_days++;
                        $this->streak_end_date = $today;
                        break;
                    }
                }
            }

            return $next($request);
        });
    }
}