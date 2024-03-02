<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\UserAnswerHeader;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct() {
        
    }

    protected function randomString($length) {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }

    protected function decrypt($message) {
        if(strpos($message, "-") !== false){
            list($crypted_token, $enc_iv) = explode("-", $message);
            $cipher_method = 'aes-128-ctr';
            $token = openssl_decrypt($crypted_token, $cipher_method, env('ENCRYPT_KEY'), 0, hex2bin($enc_iv));
            return $token;
        }
        
        // return wrong API key
        return "FALSE";
    }

    protected function recalculateCurrentStreak($user_id) {
        $test_histories = UserAnswerHeader::where("user_id", $user_id)->whereNotNull('ended_at')->get();
        $streak_days = 0;

        if(count($test_histories) > 0) {
            $yesteday = date('Y-m-d', strtotime("-1 days"));
            $compare_date = $yesteday;

            $today = date("Y-m-d");
            $streak_started_date = $today;
            $streak_end_date = null;
            
            while(1) {
                $flag = 0;
                foreach($test_histories as $history) {
                    if(date('Y-m-d', strtotime($history->ended_at)) == $compare_date) {
                        $flag = 1;
                        break;
                    }
                }

                if($flag == 1) {
                    $streak_days++;
                    $streak_started_date = $compare_date;
                    
                    if(!$streak_end_date)
                        $streak_end_date = $compare_date;

                    $compare_date = date('Y-m-d', strtotime($compare_date . ' -1 day' ) );
                } else {
                    break;
                }
            }

            // check today test as well
            foreach($test_histories as $history) {
                if(date('Y-m-d', strtotime($history->ended_at)) == $today) {
                    $streak_days++;
                    $streak_end_date = $today;
                    break;
                }
            }
        }

        return  $streak_days;
    }

    
    protected function xorEncrypt($data, $key) {
        $result = '';
        for ($i = 0; $i < strlen($data); $i++) {
            $result .= $data[$i] ^ $key[$i % strlen($key)];
        }
        return $result;
    }
    
    protected function truncateToLength($data, $length) {
        return substr($data, 0, $length);
    }
    
    protected function encryptAndTruncate($originalString, $key) {
        $encrypted = $this->xorEncrypt($originalString, $key);
        $truncated = $this->truncateToLength($encrypted, 5);
        return base64_encode($truncated);
    }
    
    protected function decryptString($data, $key) {
        $decoded = base64_decode($data);
        $decrypted = $this->xorEncrypt($decoded, $key);
        return $decrypted;
    }
}
