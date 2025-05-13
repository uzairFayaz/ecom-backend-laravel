<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\People;
use Illuminate\Auth\RequestGuard;


class AuthController extends Controller
{

    public function getUser(){
        $users = People::all();
        return response()->json($users);
    }
    public function signup(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),[
                'name' => 'required',
                'email'=> 'required|email|unique:people,email',
                'password' => 'required'
            ]
        );
        if($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->error()->all()], 401);
        }
            $user = People::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password' => $request->password,
            ]);
            return response()->json([
                'status' => true,
                'message'=>'successfull',
                'user'=> $user,
            ],200);

    }
    /*public function login(Request $request)
    {
        $validateUser = validator::make(
            $request->all(),[
                'email'=>'required|email',
                'password' =>'required'

            ]
        );
        if($validateUser->fails()){
            return response()->json([
                'status'=>false,
                'message'=>'authenticaion failed',
                'errors'=>$validateUser->errors()->all()
            ],401);
        }



        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $authUser = Auth::user();
            return response()->json([
                'status' => true,
                'message' => 'user logged sucessfully',
                'token' => $authUser->createToken("API TOKEN")->plainTextToken,
                'token_type' => 'bearer'

            ], 200);

        }else{
            return response()->json([
                'status' => false,
                'message' => 'email and password does not matched'
            ],401);
        }

    }*/public function login(Request $request)
{
    $validateUser = Validator::make(
        $request->all(),
        [
            'email' => 'required|email',
            'password' => 'required'
        ]
    );

    if ($validateUser->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Authentication failed',
            'errors' => $validateUser->errors()->all()
        ], 401);
    }

    $user = People::where('email', trim($request->email))->first();

    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'No user found with email: ' . trim($request->email)
        ], 401);
    }

    if (!Hash::check(trim($request->password), $user->password)) {
        return response()->json([
            'status' => false,
            'message' => 'Password does not match'
        ], 401);
    }

    return response()->json([
        'status' => true,
        'message' => 'User logged in successfully',
        'token' => $user->createToken('API TOKEN')->plainTextToken,
        'token_type' => 'bearer'
    ], 200);
}

    public function logout(Request $request){
        $user = $request->user();
        if(!$user){
            return response()->json([
                'status' => false,
                'message' => 'invalid token'
            ],401);
        }
       $request->$user->currentAccessTokens()->delete();
        return response()->json([
            'status' => true,
            'user' => $user,
            'message' => 'you have sucessfully logout '
        ],200);
    }
}
