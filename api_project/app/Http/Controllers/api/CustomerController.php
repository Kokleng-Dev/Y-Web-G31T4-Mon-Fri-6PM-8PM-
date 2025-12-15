<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Validator;

class CustomerController extends Controller
{
    public function index(Request $r){
        $per_page = $r->per_page ?? 10;
        $customers = Customer::with('orders')->paginate($per_page);
        return response()->json($customers, 200);
    }

    public function create(Request $r){
        $validator = Validator::make($r->all(), [
            'name' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        $customer = new Customer();
        $customer->name = $r->name;
        $customer->save();
        return response()->json($customer, 201);
    }

    public function detail($id){
        $customer = Customer::find($id);
        if($customer){
            return response()->json($customer, 200);
        }
        return response()->json(null, 404);
    }

    public function update(Request $r, $id){
        $validator = Validator::make($r->all(), [
            'name' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        $customer = Customer::find($id);
        if($customer){
            $customer->name = $r->name;
            $customer->save();
            return response()->json($customer, 200);
        }
        return response()->json(null, 404);
    }

    public function delete($id){
        $customer = Customer::find($id);
        if($customer){
            $customer->delete();
            return response()->json(null, 204);
        }
        return response()->json(null, 404);
    }
}
