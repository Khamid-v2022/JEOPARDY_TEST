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

    public function importQuestion(Request $request) {
        // set 2 hours
        set_time_limit(7200);
          
        // $validatedData = $request->validate([
        //     'files' => 'required',
        //     'files.*' => 'mimes:csv'
        // ]);

        $validatedData = $request->validate([
            'files.*' => 'mimes:csv'
        ]);
        
        
        if($request->hasFile('files0')){
            $file = $request->file('files0');
            $filename = time() . '_' . $file->getClientOriginalName();
            // File extension
            $extension = $file->getClientOriginalExtension();

            // File upload location
            $location = 'files';
            // Upload file
            $file->move($location, $filename);
            // File path
            $filepath = public_path() . '/files/' . $filename;

            // insert questions from file
            $this->read_csv($filepath);

            unlink($filepath);
        }
        return response()->json(['code'=>200, 'message'=>'File uploaded.'], 200);
       
    }

    private function read_csv($filepath){
        set_time_limit(3000);

        $file = fopen($filepath, 'r');
    
        $header = fgetcsv($file);
    
        $rows = [];
        while ($row = fgetcsv($file)) {
            // $rows[] = array_combine($header, $row);
            $rows[] = $row;

            OriginalQuestion::updateOrCreate(
                [
                    'category' => $row[0],
                    'question' => $row[2],
                    'answer' => $row[3]
                ], 
                [
                    'category' => $row[0],
                    'value' => $row[1],
                    'question' => $row[2],
                    'answer' => $row[3]
                ]);
        }
    
        fclose($file);

    }

}