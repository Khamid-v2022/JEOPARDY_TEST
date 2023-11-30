<?php 
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Hash;
use Session;

use Illuminate\Support\Facades\Mail;
use App\Mail\JStudyAppSupportEmail;

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

    public function forgot_password_page() {
        return view('pages.forgot-password');
    }

    public function sendEmailToResetPassword(Request $request) {
        $request->validate([
			'email' => 'required',
		]);

        $user = User::where('email', $request->email)->first();
		if(!$user)
			return response()->json(['code'=>401, 'message'=>'This is an unregistered email.'], 401);

        $verify_code = $this->randomString(99);
        $user->verify_code = $verify_code;
        $user->save();

        $active_link = route('reset-password', ['unique_str' => $verify_code]);

        $subject = "🔒 Password Reset Request for J!StudyApp: Regain Access to Your Account";
		$html = "<h2 class='email-title'>Password Reset Request</h2>";
		$html .= "<p>Dear {$user->name},</p>";
		$html .= "<p>We received a request to reset the password for your J!StudyApp account. To regain access and set a new password, please follow the simple steps below:</p>";
		$html .= '<p>Click the button below to initiate the password reset process:</p>';

		$html .= "<div class='two-step-code-wrapper'>";
			$html .= '<a class="link-type-button" href="' . $active_link . '" style="color: white;
            padding: 12px 24px;
            background: #3869D4;
            border-radius: 8px;
            display: inline-block;
            margin-bottom: 7px;
            text-decoration: none;">Reset Password</a>';
		$html .= "</div>";

		$html .= "<p>If the button above doesn't work, you can copy and paste the following URL into your web browser: </p>";
		$html .= '<p><a href="' . $active_link . '">' . $active_link . '</a></p></li>';
			
		$html .= '<p>By clicking the button or accessing the provided URL, you will be directed to a secure page where you can create a new password.</p>';
		$html .= "<p>Best regards,<br>
		jstudy.app</p>";

        $details = [
			'body' => $html 
		];
		
		try {
			Mail::to($user->email) -> send(new JStudyAppSupportEmail($subject, $details));
		} catch (Exception $e) {
			if (count(Mail::failures()) > 0) {
				// return redirect('/pages/misc-error');
                return response()->json(['code'=>500, 'message'=>'Email send failed'], 500);
			}
		}

		return response()->json(['code'=>200, 'message'=>'Please check your email box'], 200);
    }

    public function reset_password_page($verify_code){
		$user = User::where('verify_code', $verify_code)->first();
		
		if(!$user){
			// return redirect('/pages/misc-error');
            echo "Verify code expired";
		}
		return view('pages.reset-password', ['user' => $user]);
	}

    public function reset_password(Request $request){
		$request->validate([
			'email' => 'required',
			'password' => 'required',
		]);

		$user = User::where('email', strtolower($request->email))->first();
		if(!$user){
			return response()->json(['code'=>400, 'message'=>'Invalid email address'], 400);
		}

		$user->password = Hash::make($request->password);
        $user->verify_code = NULL;
		$user->save();

		return response()->json(['code'=>200, 'message'=>'Success', 'data'=>$user], 200);
	}

    
}