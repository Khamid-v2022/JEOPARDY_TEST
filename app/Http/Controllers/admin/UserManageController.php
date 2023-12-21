<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\UserAnswerHeader;
use App\Models\UserAnswer;

use Illuminate\Support\Facades\DB;

class UserManageController extends Controller
{
    public function index()
    {
        $users = User::get();
        return view('pages.admin.user-management', ['users' => $users]);
    }

    public function getUserInfo($id) {
        $user = User::where('id', $id)->first();
        return response()->json(['code'=>200, 'user'=>$user], 200);
    }

    public function viewUserTestInfo($user_id) {
        $user = User::where('id', $user_id)->first();
        $user_tests = UserAnswerHeader::where('user_id', $user_id)->whereNotNull('ended_at')->orderBy('created_at', 'DESC')->get();
        if(count($user_tests) > 0) {
            for($index = 0; $index < count($user_tests); $index++) {
                $diff = $user_tests[$index]->get_test_time();
                $user_tests[$index]['progress_time'] = $diff['formated'];
                $user_tests[$index]['progress_time_second'] = $diff['second'];
            }
        }
        
        return view('pages.admin.user-test-info', [
            'user_info' => $user,
            'history' => $user_tests
        ]);
    }

    public function getUserScoreForChart($user_id) {
        // get last 7 days
        $results =  UserAnswerHeader::where('user_id', $user_id)->whereNotNull('ended_at')
        ->select(DB::raw('DATE(ended_at) AS date'), DB::raw('SUM(score) AS score'), DB::raw('SUM(number_of_questions) AS number_of_questions'))->groupBy('date')
        ->orderBy('date', 'DESC')->take(7)->get();
        
        return response()->json(['code'=>200, 'scores'=>$results], 200);
    }

    public function viewTestDetail($header_id) {
        $header = UserAnswerHeader::where('id', $header_id)->first();
        $user = User::where('id', $header->user_id)->first();
        $details = UserAnswer::where('header_id', $header_id)->get();

        return view('pages.admin.view-test-detail', [
            'user_info' => $user,
            'header' => $header,
            'details' => $details
        ]);
    }

}