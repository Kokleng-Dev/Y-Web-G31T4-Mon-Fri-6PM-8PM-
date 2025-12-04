<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionFeature extends Model
{
    use SoftDeletes;

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id', 'id');
    }
    public function rolePermissions()
    {
        return $this->hasMany(RolePermission::class, 'permission_feature_id', 'id');
    }
}
