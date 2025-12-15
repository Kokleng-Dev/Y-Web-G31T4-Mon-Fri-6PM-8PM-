<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Customer;
use App\Models\OrderDetail;

class Order extends Model
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

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
    public function order_details(){
        return $this->hasMany(OrderDetail::class);
    }
}
