<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OriginalQuestion;
use App\Models\Category;
use App\Models\Question;


class DashboardController extends MyController {

    public function index() {
        return view('pages.dashboard');
    }

    public function read_csv(){
        set_time_limit(3000);

        // $filePath = storage_path('app/questions.csv');
        // $file = fopen($filePath, 'r');
    
        // $header = fgetcsv($file);
    
        // $rows = [];
        // while ($row = fgetcsv($file)) {
        //     // $rows[] = array_combine($header, $row);
        //     $rows[] = $row;
        //     // OriginalQuestion::create([
        //     //     'category' => $row[0],
        //     //     'value' => $row[1],
        //     //     'question' => $row[2],
        //     //     'answer' => $row[3]
        //     // ]);
        // }
    
        // fclose($file);
    }

    public function structure_question(){
        set_time_limit(3000);
        // $this->make_category_from_origin();
        // $this->make_query_from_origin();
    }

    private function make_category_from_origin() {
        $categories = OriginalQuestion::distinct('category')->get('category');
        
        foreach($categories as $category){
            // echo $category['category'] . "<br>";
            Category::create(['category' => $category['category']]);
        }
        echo "Category END";
    }

    private function make_query_from_origin() {
        $questions = OriginalQuestion::get();
        
        foreach($questions as $question){
            $category = Category::where('category', $question->category)->first();
            $value = $this->getIntegerFromStr($question->value);
            if($category){
                Question::create([
                    'category_id' => $category->id,
                    'question' => trim($question->question),
                    'answer' => trim($question->answer),
                    'value' => $value
                ]);
            }
        }
        echo "Question END";
    }

    private function getIntegerFromStr($str) {
        $numberWithoutCommas = (int)str_replace('$', '', str_replace(',', '', $str));

        return $numberWithoutCommas; 
    }
}