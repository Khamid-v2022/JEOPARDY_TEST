<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\OriginalQuestion;
use App\Models\UserAnswer;
use App\Models\FeatureTaskHeader;
use App\Models\FeatureTaskQuestion;

use Illuminate\Support\Facades\Validator;

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
        if(!str_contains(strtolower($request->question), "the clue") && !str_contains(strtolower($request->category), "the clue") && $request->question == strip_tags($request->question)){
            OriginalQuestion::updateOrCreate(['id' => $request->sel_id], [
                'category' => strip_tags($request->category),
                'question' => strip_tags($request->question),
                'answer' => strip_tags($request->answer)]);
        }
        

        return response()->json(['code'=>200, 'message'=>'success'], 200);
    }

    public function deleteQuestion($id) {
        OriginalQuestion::where('id', $id)->delete();
        // UserAnswer::where('question_id', $id)->delete();
        
        return response()->json(['code'=>200, 'message'=>'success'], 200);
    }

    public function importQuestion(Request $request) {
        // set 2 hours
        set_time_limit(7200);

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
            $rows[] = $row;

            if($row[2] == strip_tags($row[2])) {
                if(!str_contains(strtolower($row[2]), "the clue") && !str_contains(strtolower($row[0]), "the clue")) {
                    OriginalQuestion::updateOrCreate(
                        [
                            'category' => strip_tags($row[0]),
                            'question' => $row[2],
                            'answer' => strip_tags($row[3])
                        ], 
                        [
                            'category' => strip_tags($row[0]),
                            'value' => strip_tags($row[1]),
                            'question' => $row[2],
                            'answer' => strip_tags($row[3])
                        ]
                    );
                }
            }
        }
    
        fclose($file);

    }


    public function featuredQuestionsPage() {
        $feature_tasks = FeatureTaskHeader::get();
        return view('pages.admin.featured-questions', ['feature_tasks' => $feature_tasks]);
    }

    public function importFeaturedTask(Request $request) {
        $validatedData = $request->validate([
            'files.*' => 'mimes:csv'
        ]);

        $exist = FeatureTaskHeader::where('title', $request->title)->get();
        if(count($exist)) {
            return response()->json(['code'=>201, 'message'=>'Please use a different title. This title is already in use.'], 200);
        }

        $feature_task = FeatureTaskHeader::create([
            'title' => $request->title
        ]);

        if($request->file('thumbnail')){
            $validator = Validator::make($request->all(), [
                'thumbnail' => 'required|mimes:png,jpg,jpeg,csv,txt,pdf|max:2048'
            ]);
            
            if ($validator->fails()) {
                return response()->json(['code'=>400, 'message'=> $validator->errors()->first('file')], 400);
            } else {
                $file = $request->file('thumbnail');
                $filename = time() . '_' . $file->getClientOriginalName();
        
                // File extension
                $extension = $file->getClientOriginalExtension();
        
                // File upload location
                $location = 'assets/img/feature_task_thumbnails/';
        
                // Upload file
                $file->move($location, $filename);
                $filepath = url($location . $filename);
            } 
        } 

        if(isset($filepath)){
            $feature_task->thumbnail = $filepath;
            $feature_task->save();
        }
            

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
            $this->add_feature_questions_from_csv($feature_task->id, $filepath);

            unlink($filepath);
        }

        return response()->json(['code'=>200, 'message'=>'File uploaded.'], 200);
    }

    private function add_feature_questions_from_csv($header_id, $filepath){
        set_time_limit(3000);

        $file = fopen($filepath, 'r');
    
        $header = fgetcsv($file);
    
        $rows = [];
        while ($row = fgetcsv($file)) {
            $rows[] = $row;

            if($row[2] == strip_tags($row[2])) {
                if(!str_contains(strtolower($row[2]), "the clue") && !str_contains(strtolower($row[0]), "the clue")) {
                    FeatureTaskQuestion::create(
                        [
                            'header_id' => $header_id,
                            'category' => strip_tags($row[0]),
                            'question' => $row[2],
                            'answer' => strip_tags($row[3])
                        ]
                    );
                }
            }
        }
    
        fclose($file);

    }

    public function deleteFeaturedTask($task_id) {
        FeatureTaskQuestion::where('header_id', $task_id)->delete();
        FeatureTaskHeader::where('id', $task_id)->delete();
        return response()->json(['code'=>200, 'message'=>'Deleted'], 200);
    }

    // public function insert_question_info_to_answer_table() {
    //     $answers = UserAnswer::whereNull("question")->get();
    //     foreach($answers as $answer) {
    //         $question = OriginalQuestion::where(['id' => $answer->question_id])->first();
    //         UserAnswer::where(['id' => $answer->id])->update([
    //             'question' => $question->question,
    //             'answer' => $question->answer,
    //             'value' => $question->value
    //         ]);
    //     }
    // }

    // public function remove_questions_have_html() {
    //     $questions = OriginalQuestion::get();
    //     foreach($questions as $question) {
    //         if($question->question != strip_tags($question->question)) {
    //             OriginalQuestion::where(['id' => $question->id])->delete();
    //         }
    //     }
    //     return response()->json(['code'=>200, 'message'=>'success'], 200);
    // }
    
    // public function update_questions_remove_html() {
    //     $questions = OriginalQuestion::get();
    //     foreach($questions as $question) {
    //         OriginalQuestion::where(['id' => $question->id])->update([
    //             'question' => strip_tags($question->question)
    //         ]);
    //     }
    //     return response()->json(['code'=>200, 'message'=>'success'], 200);
    // }

    // public function structure_question(){
    //     set_time_limit(3000);
    //     // $this->make_category_from_origin();
    //     // $this->make_query_from_origin();
    // }

    // private function make_category_from_origin() {
    //     $categories = OriginalQuestion::distinct('category')->get('category');
        
    //     foreach($categories as $category){
    //         Category::create(['category' => $category['category']]);
    //     }
    //     echo "Category END";
    // }

    // private function make_query_from_origin() {
    //     $questions = OriginalQuestion::get();
        
    //     foreach($questions as $question){
    //         $category = Category::where('category', $question->category)->first();
    //         $value = $this->getIntegerFromStr($question->value);
    //         if($category){
    //             Question::create([
    //                 'category_id' => $category->id,
    //                 'question' => trim($question->question),
    //                 'answer' => trim($question->answer),
    //                 'value' => $value
    //             ]);
    //         }
    //     }
    //     echo "Question END";
    // }

    // private function getIntegerFromStr($str) {
    //     $numberWithoutCommas = (int)str_replace('$', '', str_replace(',', '', $str));

    //     return $numberWithoutCommas; 
    // }

}