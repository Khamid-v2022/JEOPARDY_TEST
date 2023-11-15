<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\OriginalQuestion;

class QuestionManageController extends Controller
{
    public function index()
    {
        return "Question Management page";
        // return view('pages.admin.question-management');
    }


    public function loadHistoryServerside(Request $request) {
        // $result = OriginalQuestion::getHistoryListForAdmin($request->length, $request->start, $request->order_by, $request->search_keys);
                
        // $result_total_count = $result['total_record_count'];
        $result_total_count = 0;
        $list = [];

        return response()->json(['code'=>200, 'list'=>$list, 'total_record'=> $result_total_count], 200);
    }

}