<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Hash;

class UserController extends Controller
{
    public function index(Request $r){
        $users = User::paginate($r->per_page ?? 10);
        return response()->json($users);
    }
    public function store(Request $r){
        $validate = Validator::make($r->all(), [
            'name' => 'required|string|max:75',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'nullable|integer|exists:roles,id'
        ]);
        if($validate->fails()){
            return response()->json([
                'status' => 'validate_error',
                'errors' => $validate->errors()
            ], 422);
        }

        // dd($validate->validated());

        // $user = new User();
        // $user->name = $r->name;
        // $user->email = $r->email;
        // $user->password = Hash::make($r->password);
        // $user->role_id = $r->role_id;
        // $user->save();

        $user = User::create($validate->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }

    public function get(Request $r, $id){
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }
        return response()->json($user);
    }

    public function delete(Request $r, $id){
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }
        $user->delete();
        return response()->json(
            [
                'status' => 'success',
                'message' => 'User deleted successfully'
            ]
        );
    }

    public function update(Request $r, $id){
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        $validate = Validator::make($r->all(), [
            'name' => 'sometimes|required|string|max:75',
            'email' => 'sometimes|required|string|email|max:100|unique:users,email,'.$id,
            'password' => 'sometimes|required|string|min:8',
            'role_id' => 'sometimes|nullable|integer|exists:roles,id'
        ]);
        if($validate->fails()){
            return response()->json([
                'status' => 'validate_error',
                'errors' => $validate->errors()
            ], 422);
        }

        $user->update($validate->validated());
        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }
}
