<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\PermissionFeature;
use App\Models\RolePermission;
use DB;
use Validator;

class RolePermissionController extends Controller
{
    public function index($role_id){
        $role = Role::find($role_id);
        if(!$role){
            return response()->json(['message' => 'Role not found'], 404);
        }
        // $rolePermissions = $role->rolePermissions()->with('permission.permissionFeatures')->get();
        // left join

        // $rolePermissions = Permission::leftJoin('role_permissions as rp', function ($join) use ($role_id) {
        //         $join->on('permissions.id', '=', 'rp.permission_id')
        //             ->where('rp.role_id', '=', $role_id);
        //     })
        //     ->select('permissions.id','permissions.name as permission_name')
        //     ->with('permissionFeatures', function($query){
        //         $query->select(
        //             'id',
        //             'permission_id',
        //             'name',
        //         );
        //     })
        //     ->distinct()
        //     ->get();

        $permissions = Permission::query()
                        ->select(
                            'permissions.id',
                            'permissions.name as permission_name'
                        )
                        ->with('permissionFeatures', function($query){
                            $query->select(
                                'id',
                                'permission_id',
                                'name',
                                DB::raw("0 as have_permission")
                            );
                        })
                        ->get();

        $rolePermissions = $role->rolePermissions()->get();

        foreach ($rolePermissions as $index => $rp) {
            foreach ($permissions as $x => $p) {
                if($rp->permission_id == $p->id){
                    foreach ($p->permissionFeatures as $j => $feature) {
                       if($rp->permission_feature_id == $feature->id){
                           $permissions[$x]->permissionFeatures[$j]->have_permission = 1;
                       }
                    }
                }
            }
        }

        return response()->json(['role_permissions' => $permissions], 200);
    }


    public function set_permission(Request $r){
        $validator = Validator::make($r->all(), [
            'role_id' => 'required|integer|exists:roles,id',
            'permission_id' => 'required|integer|exists:permissions,id',
            // 'permission_feature_id' => 'required|integer|exists:permission_features,id',
            // array
            'permission_feature_id' => 'required|array',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        foreach ($r->permission_feature_id as $value) {
            $role_permission = RolePermission::where([
                'role_id' => $validator->validated()['role_id'],
                'permission_id' => $validator->validated()['permission_id'],
                'permission_feature_id' => $value
            ])->first();
            if($role_permission){
                $role_permission->forceDelete();
            } else {
                DB::table('role_permissions')->insert([
                    'role_id' => $validator->validated()['role_id'],
                    'permission_id' => $validator->validated()['permission_id'],
                    'permission_feature_id' => $value
                ]);
            }
        }



        return response()->json(['message' => 'Role permission updated successfully'], 200);
    }
}
