<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\OriginalQuestion;

class QuestionManageController extends Controller
{
    public function index()
    {
        return view('pages.admin.question-management');
    }


    public function loadQuestions(Request $request) {
        $questionObj = OriginalQuestion::where('category', 'like', '%' . $request->search_key . '%')
                                        ->orWhere('question', 'like', '%' . $request->search_key . '%')
                                        ->orWhere('answer', 'like', '%' . $request->search_key . '%');
        $result_total_count = $questionObj->count();

        $list = $questionObj->limit($request->length)->offset($request->start)->get();
       

        return response()->json(['code'=>200, 'list'=>$list, 'total_record'=> $result_total_count], 200);
    }

    public function createOrUpdateQuestion(Request $request) {
        OriginalQuestion::updateOrCreate(['id' => $request->sel_id], [
                                                            'category' => $request->category,
                                                            'question' => $request->question,
                                                            'answer' => $request->answer]);

        return response()->json(['code'=>200, 'message'=>'success'], 200);
    }

    public function deleteQuestion($id) {
        OriginalQuestion::where('id', $id)->delete();
        
        return response()->json(['code'=>200, 'message'=>'success'], 200);
    }

}