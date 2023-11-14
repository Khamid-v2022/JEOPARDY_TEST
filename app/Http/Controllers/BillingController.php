<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BillingHistory;
use App\Models\UserAnswerHeader;


class BillingController extends MyController {

    public function index() {
        $tested_count = 0;
        $compare_date = '';
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

        $histories = BillingHistory::where('user_id', $this->user->id)->orderBy('created_at', 'DESC')->get();

        return view('pages.billing', [
            'histories' => $histories,
            'tested_count' => $tested_count,
            'started_this_month' => $compare_date
        ]);
    }

   
}