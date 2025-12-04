<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Validator;

class RoleController extends Controller
{
    public function index(Request $r){
        $roles = Role::paginate($r->per_page ?? 10);
        return response()->json($roles, 200);
    }
    public function create(Request $r){
        $validator = Validator::make($r->all(),[
            "name" => "required"
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $role = new Role();
        $role->name = $r->name;
        $role->save();

        return response()->json($role,201);
    }
    public function detail($id){
        $role = Role::find($id);
        if(!$role){
            return response()->json(["message" => "Role not found", "status" => "error"], 404);
        }
        return response()->json($role, 200);
    }

    public function update(Request $r, $id){
        $role = Role::find($id);
        if(!$role){
            return response()->json(["message" => "Role not found", "status" => "error"], 404);
        }

        $validator = Validator::make($r->all(), [
            "name" => "required|string"
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        $role->name = $r->name;
        $role->save();

        return response()->json($role, 200);
    }

    public function delete($id){
        $role = Role::find($id);
        if(!$role){
            return response()->json(["message" => "Role not found", "status" => "error"], 404);
        }
        $role->delete();
        return response()->json(["message" => "Role deleted successfully", "status" => "success"], 200);
    }
}
