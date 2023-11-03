<?php 
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Model\User;

use Hash;
use Sessioon;

class AuthController extends Controller {
    
    public function index() {
        return view('pages.auth-login');
    }

    public function register_page(){
        return view('pages.auth-register');
    }
}