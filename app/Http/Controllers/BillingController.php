<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BillingHistory;


class BillingController extends MyController {

    public function index() {
        $remain_days = 0;
        $total_days = 0;
        if($this->user->subscription_status == 1) {
            $today = strtotime(date("Y-m-d"));
            $sebscribed_date = strtotime($this->user->subscribed_at);
            $expire_date = strtotime($this->user->expire_at);

            $datediff = $expire_date - $today;
            $remain_days = round($datediff / (60 * 60 * 24));

            if($today > $sebscribed_date) {
                $total_diff = $expire_date - $sebscribed_date;
            } else {
                $total_diff = $expire_date - $today;
            }

            $total_days = round($total_diff / (60 * 60 * 24));
        }

        $histories = BillingHistory::where('user_id', $this->user->id)->orderBy('created_at', 'DESC')->get();

        return view('pages.billing', [
            'remain_days' => $remain_days,
            'total_days' => $total_days,
            'histories' => $histories
        ]);
    }

   
}