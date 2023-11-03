<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;


class MyController extends Controller {
    protected $user;

    public function __construct() {
        parent::__construct();

        $this->middleware(function($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
}