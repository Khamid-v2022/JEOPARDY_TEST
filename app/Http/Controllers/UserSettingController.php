<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Session;

class UserSettingController extends MyController {

    public function index() {
        return view('pages.user-setting')->with('streak_days', $this->streak_days);
    }

    public function update_profile(Request $request) {
        if($request->file('file')){
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:png,jpg,jpeg,csv,txt,pdf|max:2048'
            ]);
            if ($validator->fails()) {
                return response()->json(['code'=>400, 'message'=> $validator->errors()->first('file')], 400);
            } else {
                $file = $request->file('file');
                $filename = time() . '_' . $file->getClientOriginalName();
        
                // File extension
                $extension = $file->getClientOriginalExtension();
        
                // File upload location
                $location = 'assets/img/avatars/';
        
                // Upload file
                $file->move($location, $filename);
                $filepath = url($location . $filename);
            } 
        } 

        if(isset($filepath))
            $this->user->avatar = $filepath;

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

    public function update_email_notification(Request $request) {
        $this->user->is_receive_email = $request->is_check;
        $this->user->save();

        return response()->json(['code'=>200, 'message'=>'success'], 200);
    }
   
}