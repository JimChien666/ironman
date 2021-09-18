<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogoutController extends Controller{
    public function __invoke(Request $request)
    {
        $request->session()->flush();
        return view('welcome');
    }
}
