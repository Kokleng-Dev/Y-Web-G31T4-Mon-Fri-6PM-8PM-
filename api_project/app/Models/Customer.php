<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Order;

class Customer extends Model
{
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->created_by = auth()->user()->id;
        });
        static::updating(function ($user) {
            $user->updated_by = auth()->user()->id;
        });
        static::deleting(function ($user) {
            $user->deleted_by = auth()->user()->id;
            $user->save();
        });
    }
    public function orders(){
        return $this->hasMany(Order::class);
    }
}
