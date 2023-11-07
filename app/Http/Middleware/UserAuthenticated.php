<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class UserAuthenticated 
{
    public function handle(Request $request, Closure $next) {
        if(Auth::check()){
            return $next($request);
        }
        
        return redirect(route('pages-login'));
    }
}
