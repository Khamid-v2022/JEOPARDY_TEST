<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OriginalQuestion;
use App\Models\Category;
use App\Models\Question;
use App\Models\UserAnswerHeader;
use App\Models\UserAnswer;

class DashboardController extends MyController {

    public function index() {
        $my_tests = UserAnswerHeader::where('user_id', $this->user->id)->whereNotNull('ended_at')->orderBy('created_at', 'DESC')->get();
        if(count($my_tests) > 0) {
            for($index = 0; $index < count($my_tests); $index++) {
                $diff = $my_tests[$index]->get_test_time();
                $my_tests[$index]['progress_time'] = $diff['formated'];
                $my_tests[$index]['progress_time_second'] = $diff['second'];
            }
        }
        
        return view('pages.dashboard', [
            'history' => $my_tests
        ]);
    }


    public function delete_test_record($id) {
        UserAnswer::where('header_id', $id)->delete();
        UserAnswerHeader::where('id', $id)->delete();
        
        return response()->json(['code'=>200, 'message'=>'success'], 200);
    }



    // public function read_csv(){
    //     set_time_limit(3000);

    //     // $filePath = storage_path('app/questions.csv');
    //     // $file = fopen($filePath, 'r');
    
    //     // $header = fgetcsv($file);
    
    //     // $rows = [];
    //     // while ($row = fgetcsv($file)) {
    //     //     // $rows[] = array_combine($header, $row);
    //     //     $rows[] = $row;
    //     //     // OriginalQuestion::create([
    //     //     //     'category' => $row[0],
    //     //     //     'value' => $row[1],
    //     //     //     'question' => $row[2],
    //     //     //     'answer' => $row[3]
    //     //     // ]);
    //     // }
    
    //     // fclose($file);
    // }

    // public function structure_question(){
    //     set_time_limit(3000);
    //     // $this->make_category_from_origin();
    //     // $this->make_query_from_origin();
    // }

    // private function make_category_from_origin() {
    //     $categories = OriginalQuestion::distinct('category')->get('category');
        
    //     foreach($categories as $category){
    //         // echo $category['category'] . "<br>";
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