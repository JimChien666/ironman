<?php

namespace App\Http\Controllers\api;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(User::select('id', 'name', 'account')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, UserService $userService)
    {
        $account = $request->account;
        $password = $request->password;
        $username = $request->username;
        try{
            if($userService->signUp($account, $password, $username)){
                return response()->json([
                    'success' => 'true'
                ]);
            }
        } catch (Exception $e){
            return response()->json([
                'success' => 'false',
                'error'=> $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(User::select('id', 'name', 'account')->where('id', $id)->first());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if($user->update($request->all())){
            return response()->json([
                'success' => 'true'
            ]);
        }
        return response()->json([
            'success' => 'false'
        ]);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(User::find($id)->delete()){
            return response()->json([
                'success' => 'true'
            ]);
        }
        return response()->json([
            'success' => 'false'
        ]);
    }
}
