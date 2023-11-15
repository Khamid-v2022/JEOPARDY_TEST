<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BillingHistory;
use App\Models\UserSubscription;

use Stripe;
use Stripe\Plan;

class CheckoutController extends MyController {

    public $api_error = '';

    public function index($plan) {
        if($this->user->subscription_status == 1){
            return redirect('/');
        }

        if($plan == "monthly") {
            $price = env('MONTHLY_PLAN_PRICE');
            $plan = 'Monthly';
        } else if($plan == "annually") {
            $price = env('ANNUALLY_PLAN_PRICE');
            $plan = 'Annually';
        } else {
            return redirect('/');
        }
       
        return view('pages.checkout', [
            'price' => $price,
            'plan' => $plan
        ]);
    }

    public function pricing_page() {
        return view('pages.pricing');
    }

    // One time payment
    // public function upgrade_account(Request $request) {
    //     $pack_name = 'Premium';        
        
    //     Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
    //     $charge = null;
    //     $amount = env('MONTHLY_PLAN_PRICE');
    //     $period = 1;

    //     try {
    //         $charge = Stripe\Charge::create ([
    //             "amount" => $amount * 100,
    //             "currency" => env('STRIPE_CURRENCY'),
    //             "source" => $request->token,
    //             "description" => "Subscription free for " . $request->name,
    //         ]);
    //     } catch (Exception $e) {
    //         $charge['error'] = $e->getMessage();
    //     }
        
    //     if (!empty($charge) && $charge['status'] == 'succeeded') {

    //         // success
    //         // calculate expire date from today
    //         $today = date("Y-m-d");
    //         if($this->user->subscription_status == 1 && strtotime($this->user->expire_at) > strtotime($today)){
    //             // calculate from expire_at
    //             $start_date = $this->user->expire_at;
    //         } else {
    //             $start_date = $today;
    //         }

    //         $expire_date = date('Y-m-d', strtotime("+" . $period  . " months", strtotime($start_date))); 

    //         // active account
    //         $this->user->subscription_status = 1;
    //         $this->user->subscribed_at =  $start_date;
    //         $this->user->expire_at = $expire_date;
    //         $this->user->save();

    //         BillingHistory::create([
    //             'user_id' => $this->user->id,
    //             'card_number' => '',
    //             'method' => 'Stripe',
    //             'package' => $pack_name,
    //             'amount' => $amount,
    //             'period' => $period . " Month",
    //             'reference' => json_encode($charge),
    //             'transaction_id' => $charge->id,
    //             'status' => 1,
    //             'detail_name' => $request->full_name,
    //             'detail_address' => $request->address,
    //             'detail_city' => $request->city,
    //             'detail_zipcode' => $request->zip_code,
    //             'detail_country' => $request->country
    //         ]);
    //         return response()->json(['code'=>200, 'message'=>''], 200);
    //     } else {
    //         // failed
    //         return response()->json(['code'=>400, 'message'=>'Payment failed.'], 200);
    //     }
    // }

    public function upgrade_account_with_subscription(Request $request) {
        $pack_name = 'Annually';
        $amount = env('ANNUALLY_PLAN_PRICE');

        if($request->period == "month"){
            $pack_name = 'Monthly'; 
            $amount = env('MONTHLY_PLAN_PRICE');    
        }
          
        
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $charge = null;
       
        $period = "1 " . $request->period;

        // check User is in subscribers...
        if($this->user->subscription_status == 1) {
            return response()->json(['code'=>400, 'message'=> "You are already subscribed"], 200);
        }

        $subscriper = UserSubscription::where('customer_email', $this->user->email)->where('status', 'active')->latest()->first();
        if($subscriper) {
            if(strtotime($subscriper->plan_period_end) > strtotime(date('Y-m-d H:i:s'))){
                return response()->json(['code'=>400, 'message'=> "You are already subscribed"], 200);
            }
        }

        $customer = $this->addCustomer($this->user->name, $this->user->email, $request->token);
        
        if(!$customer) { 
            // failed
            return response()->json(['code'=>400, 'message'=> $customer], 200);
        }
        
        $plan = $this->createPlan($pack_name . ' Plan', $amount, $request->period);
            
        if(!$plan){ 
            return response()->json(['code'=>400, 'message'=> $customer], 200);
        }

        $subscription = $this->createSubscription($customer->id, $plan->id);
        if(!$subscription) {
            return response()->json(['code'=>400, 'message'=> $customer], 200);
        }

        if($subscription['status'] == 'active'){ 
            // success
            $this->user->subscription_status = 1;
            $this->user->subscribed_at = date("Y-m-d H:i:s", $subscription['current_period_start']);
            $this->user->subscription_plan = $pack_name;
            $this->user->save();                    

            UserSubscription::create([
                'user_id' => $this->user->id,
                'plan_id' => $plan->id,
                'stripe_customer_id' => $customer->id,
                'stripe_plan_price_id' => $subscription['plan']['id'],
                'stripe_payment_intent_id' => '',
                'stripe_subscription_id' => $subscription['id'],
                'default_payment_method' => $subscription['default_payment_method'],
                'default_source' => $subscription['default_source'],
                'paid_amount' => $amount,
                'paid_amount_currency' => env('STRIPE_CURRENCY'),
                'plan_interval' => $subscription['plan']['interval'],
                'plan_interval_count' => $subscription['plan']['interval_count'],
                'customer_name' => $this->user->name,
                'customer_email' => $this->user->email,
                'plan_period_start' => date("Y-m-d H:i:s", $subscription['current_period_start']),
                'plan_period_end' => date("Y-m-d H:i:s", $subscription['current_period_end']),
                'status' => $subscription['status']
            ]);

            BillingHistory::create([
                'user_id' => $this->user->id,
                'card_number' => '',
                'method' => 'Stripe',
                'package' => $pack_name,
                'amount' => $amount,
                'period' => $period,
                'reference' => json_encode($subscription),
                'transaction_id' => $subscription['id'],
                'status' => 1,
                'detail_name' => $request->full_name,
                'detail_address' => $request->address,
                'detail_city' => $request->city,
                'detail_zipcode' => $request->zip_code,
                'detail_country' => $request->country
            ]);

            // $output = [
            //     'customerId' => $customer->id,
            //     'subscription' => $subscription
            // ]; 

            return response()->json(['code'=>200, 'message'=>'success'], 200);
        }

        // failed
        return response()->json(['code'=>400, 'message'=>''], 200);
        
    }

    public function cancel_subscription() {
        // get subscrition_id
        $subscriber = UserSubscription::where('customer_email', $this->user->email)->where('status', 'active')->latest()->first();
        if($subscriber) {
            try { 
                // Creates a new subscription 
                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
    
                $response = $stripe->subscriptions->cancel(
                    $subscriber->stripe_subscription_id,
                    ['prorate' => 'true']
                );

                if($response) {
                    $subscriber->status = 'canceled';
                    $subscriber->save();

                    $this->user->subscription_status = 0;
                    $this->user->save();

                    return response()->json(['code'=>200, 'message'=>'success'], 200);
                }
            } catch(Exception $e) { 
                $this->api_error = $e->getMessage(); 
            } 
        }

        return response()->json(['code'=>400, 'message'=>'Something went wrong'], 200);
        
    }


    private function addCustomer($name, $email, $token) {
        try {
            $customer = \Stripe\Customer::create([ 
                'name' => $name,  
                'email' => $email,
                'source' => $token
            ]); 
            return $customer;
        } catch (Exception $e) {
            $this->$api_error = $e->getMessage();
            return false;
        }
    }

    private function createPlan($planName, $planPrice, $planInterval) {
        // Convert price to cents 
        $priceCents = ($planPrice * 100); 
        $currency = env('STRIPE_CURRENCY'); 
         
        try { 
            // Create a plan 
            $plan = \Stripe\Plan::create(array( 
                "product" => [ 
                    "name" => $planName 
                ], 
                "amount" => $priceCents, 
                "currency" => $currency, 
                "interval" => $planInterval, 
                "interval_count" => 1 
            )); 
            return $plan; 
        }catch(Exception $e) { 
            $this->api_error = $e->getMessage(); 
            return false; 
        } 
    }

    private function createPrice($planName, $planPrice, $planInterval){ 
        $priceCents = ($planPrice * 100); 
        try { 
            // Create price with subscription info and interval 
            $price = \Stripe\Price::create([ 
                'unit_amount' => $priceCents, 
                'currency' => env('STRIPE_CURRENCY'), 
                'recurring' => ['interval' => $planInterval, 'interval_count' => 1], 
                'product_data' => ['name' => $planName], 
            ]); 
            return $price;
        } catch (Exception $e) {  
            $this->api_error = $e->getMessage(); 
            return false; 
        } 
    } 

    private function createSubscription($customerID, $planID){ 
        try { 
            // Creates a new subscription 
            $subscription = \Stripe\Subscription::create([ 
                'customer' => $customerID, 
                'items' => [[ 
                    'plan' => $planID, 
                ]]
            ]); 
             
            // Retrieve charge details 
            $subsData = $subscription->jsonSerialize(); 
            return $subsData; 
        }catch(Exception $e) { 
            $this->api_error = $e->getMessage(); 
            return false; 
        } 
    } 

    private function createCharge($planPrice, $customerID) {
        $priceCents = ($planPrice * 100); 
        try { 
            // Creates a new subscription 
            $charge = \Stripe\Charge::create([ 
                'amount' => $priceCents, 
                'currency' => env('STRIPE_CURRENCY'),
                'customer' => $customerID
            ]); 

            return $charge; 
        } catch(Exception $e) { 
            $this->api_error = $e->getMessage(); 
            return false; 
        } 
    }
}