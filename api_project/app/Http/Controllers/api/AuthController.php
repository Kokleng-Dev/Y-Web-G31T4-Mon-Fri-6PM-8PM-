<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Auth;
use Validator;

class AuthController extends Controller
{
    public function register(Request $r){
        $user = User::create([
            "name" => $r->name,
            "email" => $r->email,
            "password" => Hash::make($r->password)
        ]);
        return response()->json([
            "user" => $user,
            "message" => "User created successfully"
        ], 201);
    }

    public function login(Request $r){
        $validator = Validator::make($r->all(),[
            'email' => 'required|email',
            'password'=> 'required|min:6'
        ]);
        if($validator->fails()){
            return response()->json([
                "status" => "validate_error",
                "errors" => $validator->errors()
            ], 422);
        }
        // dd($validator->validated());
        // dd(Auth::attempt($validator->validated()));


        if(Auth::attempt($validator->validated())){
            return response()->json([
                "status" => "success",
                "access_token" => Auth::user()->createToken("access_token")->plainTextToken,
            ]);
        }
        return response()->json([
            "status" => "error",
            "message" => "Invalid credentials"
        ], 401);
    }

    public function logout(Request $r){
        $r->user()->currentAccessToken()->delete();
        return response()->json([
            "status" => "success",
            "message" => "User logged out successfully"
        ], 200);
    }
}
