<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;


class CheckoutController extends MyController {

    public function index() {
        return view('pages.checkout');
    }

   
}