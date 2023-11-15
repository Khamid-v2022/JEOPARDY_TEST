<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;

class UserManageController extends Controller
{
    public function index()
    {
        $users = User::get();
        return view('pages.admin.user-management', ['users' => $users]);
    }

    public function getUserInfo($id) {
        $user = User::where('id', $id)->first();
        return response()->json(['code'=>200, 'user'=>$user], 200);
    }

}