<?php 
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Hash;
use Session;

class AuthController extends Controller {

    public function index() {
        return view('pages.auth-login');
    }

    public function do_login(Request $request) {
        $request->validate([
            'email'     => 'required',
            'password'  => 'required'
        ]);

        $user = User::where('email', strtolower(trim($request->email)))->first();
        if(!$user)
            return response()->json(['code' => 401, 'message' => "We couldn't verify your account with that information"], 401);

        // check expire date
        // if($user->subscription_status == 1) {
        //     $today = date("Y-m-d");
        //     if(strtotime($user->expire_at) < strtotime($today)){
        //         $user->subscription_status = 0;
        //         $user->save();
        //     }
        // }

        if($user->is_delete == 1) {
            return response()->json(['code' => 401, 'message' => "This account is not activated"], 401);
        }

        if(!Hash::check($request->password, $user->password)) {
            return response()->json(['code' => 401, 'message' => "We couldn't verify your account with that information"], 401);
        }

        $credentials = $request->only('email', 'password');
        if(Auth::attempt($credentials)) {
            return response()->json(['code'=>200, 'message'=>'success'], 200);
        }
        
        return response()->json(['code' => 401, 'message' => "We couldn't verify your account with that information"], 401);

    }

    public function do_logout() {
        Session::flush();
        if(Auth::check()) {
            Auth::logout();
        }

        return redirect('/login');
    }

    public function register_page(){
        return view('pages.auth-register');
    }

    public function do_register(Request $request) {
        $request->validate([
            'name'      => 'required',
            'email'     => 'required',
            'password'  => 'required'
        ]);

        $email = strtolower(trim($request->email));

        $is_exist = User::where('email', $email)->get();
        if(count($is_exist) > 0) {
            return response()->json(['code' => 409, 'message'=>'The email address you entered is already in use by another user.'], 409);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json(['code'=>200, 'message'=>'success'], 200);
    }
}