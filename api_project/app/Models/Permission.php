<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;

    public function permissionFeatures()
    {
        return $this->hasMany(PermissionFeature::class, 'permission_id', 'id');
    }
}
