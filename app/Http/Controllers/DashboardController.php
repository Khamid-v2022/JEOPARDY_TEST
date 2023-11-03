<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OriginalQuestion;


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
}