<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PermissionFeature;
use Validator;

class PermissionFeatureController extends Controller
{
    public function index(Request $r){
        $permission_features = PermissionFeature::query();
        if($r->has("permission_id")){
            $permission_features = $permission_features->where("permission_id", $r->permission_id);
        }
        $permission_features = $permission_features->paginate($r->per_page ?? 10);
        return response()->json($permission_features, 200);
    }
    public function create(Request $r){
        $validator = Validator::make($r->all(),[
            'permission_id' => 'required',
            "name" => "required",
            "key" => "required|unique:permissions,key",
            "description" => "sometimes|string"
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $permission_feature = new PermissionFeature();
        $permission_feature->permission_id = $r->permission_id;
        $permission_feature->name = $r->name;
        $permission_feature->key = $r->key;
        $permission_feature->description = $r->description;
        $permission_feature->save();

        return response()->json($permission_feature,201);
    }
    public function detail($id){
        $permission_feature = PermissionFeature::find($id);
        if(!$permission_feature){
            return response()->json(["message" => "Permission Feature not found", "status" => "error"], 404);
        }
        return response()->json($permission_feature, 200);
    }

    public function update(Request $r, $id){
        $permission_feature = PermissionFeature::find($id);
        if(!$permission_feature){
            return response()->json(["message" => "Permission Feature not found", "status" => "error"], 404);
        }

        $validator = Validator::make($r->all(), [
            "permission_id" => "sometimes|required",
            "name" => "required|string",
            "key" => "sometimes|unique:permissions,key",
            "description" => "sometimes|string"
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        $permission_feature->name = $r->name;
        if (isset($r->key)) {
            $permission_feature->key = $r->key;
        }
        if (isset($r->permission_id)) {
            $permission_feature->permission_id = $r->permission_id;
        }
        $permission_feature->description = $r->description;
        $permission_feature->save();

        return response()->json($permission_feature, 200);
    }

    public function delete($id){
        $permission_feature = PermissionFeature::find($id);
        if(!$permission_feature){
            return response()->json(["message" => "Permission Feature not found", "status" => "error"], 404);
        }
        $permission_feature->delete();
        return response()->json(["message" => "Permission deleted successfully", "status" => "success"], 200);
    }
}
