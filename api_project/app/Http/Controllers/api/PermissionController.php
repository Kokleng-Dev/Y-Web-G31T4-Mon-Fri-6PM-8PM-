<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use Validator;

class PermissionController extends Controller
{
    public function index(Request $r){
        $permissions = Permission::paginate($r->per_page ?? 10);
        return response()->json($permissions, 200);
    }
    public function create(Request $r){
        $validator = Validator::make($r->all(),[
            "name" => "required",
            "key" => "required|unique:permissions,key",
            "description" => "sometimes|string"
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $permission = new Permission();
        $permission->name = $r->name;
        $permission->key = $r->key;
        $permission->description = $r->description;
        $permission->save();

        return response()->json($permission,201);
    }
    public function detail($id){
        $permission = Permission::find($id);
        if(!$permission){
            return response()->json(["message" => "Permission not found", "status" => "error"], 404);
        }
        return response()->json($permission, 200);
    }

    public function update(Request $r, $id){
        $permission = Permission::find($id);
        if(!$permission){
            return response()->json(["message" => "Permission not found", "status" => "error"], 404);
        }

        $validator = Validator::make($r->all(), [
            "name" => "required|string",
            "key" => "sometimes|unique:permissions,key",
            "description" => "sometimes|string"
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        $permission->name = $r->name;
        if (isset($r->key)) {
            $permission->key = $r->key;
        }
        $permission->description = $r->description;
        $permission->save();

        return response()->json($permission, 200);
    }

    public function delete($id){
        $permission = Permission::find($id);
        if(!$permission){
            return response()->json(["message" => "Permission not found", "status" => "error"], 404);
        }
        $permission->delete();
        return response()->json(["message" => "Permission deleted successfully", "status" => "success"], 200);
    }
}
