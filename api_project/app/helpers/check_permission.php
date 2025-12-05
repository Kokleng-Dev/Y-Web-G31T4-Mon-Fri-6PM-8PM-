<?php
use App\Models\Role;
use App\Models\Permission;
use App\Models\PermissionFeature;
use App\Models\RolePermission;

function checkPermission($role_id,$permission_key,$feature_key){
    $role = Role::find($role_id);
    if(!$role){
        return false;
    }

    $permission = Permission::where([
        'key' => $permission_key
    ])->first();
    if (!$permission) {
        return false;
    }

    $permission_feature = PermissionFeature::where([
        'permission_id' => $permission->id,
        'key' => $feature_key
    ])->first();
    if (!$permission_feature) {
        return false;
    }

    $role_permission = RolePermission::where([
        'role_id' => $role_id,
        'permission_id' => $permission->id,
        'permission_feature_id' => $permission_feature->id
    ])->first();
    if ($role_permission) {
        return true;
    }
    return false;
}
?>
