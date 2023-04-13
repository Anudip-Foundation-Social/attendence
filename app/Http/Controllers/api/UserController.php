<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;
use Validator;

class UserController extends Controller
{
    public function loginUser(Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'password' => 'required',
        ]);
   
        if($validator->fails()){

            return Response(['message' => $validator->errors()],401);
        }
   
        if(Auth::attempt($request->all())){

            $user = Auth::user(); 
    
            $success =  $user->createToken('MyApp')->plainTextToken; 
        
            return Response(['token' => $success,'status'=>1],200);
        }

        return Response(['message' => 'user_id or password wrong','status'=>0],401);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function userDetails(): Response
    {
        if (Auth::check()) {

            $user = Auth::user();

            return Response(['data' => $user],200);
        }

        return Response(['data' => 'Unauthorized'],401);
    }

    /**
     * Display the specified resource.
     */
    public function logout(): Response
    {
        $user = Auth::user();

        $user->currentAccessToken()->delete();
        
        return Response(['data' => 'User Logout successfully.'],200);
    }
}
