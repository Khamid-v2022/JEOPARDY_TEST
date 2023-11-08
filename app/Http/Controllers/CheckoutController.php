<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BillingHistory;

use Stripe;

class CheckoutController extends MyController {
    public function index() {
        if($this->user->subscription_status == 1){
            return redirect('/');
        }
        return view('pages.checkout');
    }

    public function upgrade_account(Request $request) {
        $pack_name = 'Premium';        
        
        Stripe\Stripe::setApiKey(env('STRIPE_TEST_SECRET_KEY'));
        $charge = null;
        $amount = env('BASIC_PLAN_PRICE');
        $period = 1;

        try {
            $charge = Stripe\Charge::create ([
                "amount" => $amount * 100,
                "currency" => "USD",
                "source" => $request->token,
                "description" => "Subscription free for " . $request->name,
            ]);
        } catch (Exception $e) {
            $charge['error'] = $e->getMessage();
        }
        
        if (!empty($charge) && $charge['status'] == 'succeeded') {

            // success
            // calculate expire date from today
            $today = date("Y-m-d");
            if($this->user->subscription_status == 1 && strtotime($this->user->expire_at) > strtotime($today)){
                // calculate from expire_at
                $start_date = $this->user->expire_at;
            } else {
                $start_date = $today;
            }

            $expire_date = date('Y-m-d', strtotime("+" . $period  . " months", strtotime($start_date))); 

            // active account
            $this->user->subscription_status = 1;
            $this->user->subscribed_at =  $start_date;
            $this->user->expire_at = $expire_date;
            $this->user->save();

            BillingHistory::create([
                'user_id' => $this->user->id,
                'card_number' => '',
                'method' => 'Stripe',
                'package' => $pack_name,
                'amount' => $amount,
                'period' => $period . " Month",
                'reference' => json_encode($charge),
                'transaction_id' => $charge->id,
                'status' => 1,
                'detail_name' => $request->full_name,
                'detail_address' => $request->address,
                'detail_city' => $request->city,
                'detail_zipcode' => $request->zip_code,
                'detail_country' => $request->country
            ]);
            return response()->json(['code'=>200, 'message'=>''], 200);
        } else {
            // failed
            return response()->json(['code'=>400, 'message'=>'Payment failed.'], 200);
        }
   }
}