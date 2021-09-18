<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    public function login(Request $request){
        $account = $request->input('account');
        $password = $request->input('password');

        $user = DB::table('users')->where('account', '=', $account)
                                  ->first();
        if(Hash::check($password, $user->password)){
            return "你好，我的帳號是".$user->account;
        }
        return Redirect::back()->withErrors(['帳號或密碼錯誤']);
    }

    public function loginWithORM(Request $request){
        $account = $request->input('account');
        $password = $request->input('password');
        $user = User::query()
        ->where('account', $account)
        ->first();
        if(Hash::check($password, $user->password)){
            session(['user' => $user]);
            return view('welcome');
        }
        return Redirect::back()->withErrors(['帳號或密碼錯誤']);
    }
}
