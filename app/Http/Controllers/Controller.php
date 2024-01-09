<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

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
}
