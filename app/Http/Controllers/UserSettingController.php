<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class UserSettingController extends MyController {

    public function index() {
        return view('pages.user-setting');
    }

    public function update_profile(Request $request) {
        $this->user->name = $request->name;
        $this->user->email = $request->email;
        $this->user->address = $request->address;
        $this->user->city = $request->city;
        $this->user->zipcode = $request->zipcode;
        $this->user->country = $request->country;
        $this->user->default_question_count = $request->default_question_count;
        $this->user->save();

        return response()->json(['code'=>200, 'message'=>'success'], 200);
    }

    public function delete_profile() {
        $this->user->is_delete = 1;
        $this->user->save();

        // remove Session
        Session::flush();
        if(Auth::check()) {
            Auth::logout();
        }
        return response()->json(['code'=>200, 'message'=>'success'], 200);
    }
   
}