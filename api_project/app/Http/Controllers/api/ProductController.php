<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Product;
use DB;
class ProductController extends Controller
{
    public function index(Request $r){
        $per_page = $r->per_page ?? 10;
        $data = Product::query()
            ->with('category')
            ->paginate($per_page);
        return response()->json(['base_url' => url('/'),'results' => $data], 200);
    }

    public function create(Request $r){
        $validator = Validator::make($r->all(), [
            'name' => 'required',
            'price' => 'required',
            'category_id' => 'required|integer|exists:categories,id',
            'qty' => 'required|integer',
            'description' => 'sometimes|string',
            'image' => 'sometimes|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        $instand = new Product();
        $instand->name = $r->name;
        $instand->price = $r->price;
        $instand->category_id = $r->category_id;
        $instand->qty = $r->qty;
        $instand->description = $r->description;
        if ($r->hasFile('image')) {
            $instand->image = $r->image->store('images', 'custom');
        }
        $instand->save();
        return response()->json($instand, 201);
    }

    public function detail($id){
        $find = Product::find($id);
        if($find){
            if($find->image){
                $find->image = asset($find->image);
            }
            return response()->json($find, 200);
        }
        return response()->json(null, 404);
    }

    public function update(Request $r, $id){
        // dd(storage_path('../app/public'));
        $validator = Validator::make($r->all(), [
            'name' => 'sometimes|required',
            'price' => 'sometimes|required',
            'category_id' => 'sometimes|required|integer|exists:categories,id',
            'qty' => 'sometimes|required|integer',
            'description' => 'sometimes|string',
            'image' => 'sometimes|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        $find = Product::find($id);
        if($find){
            $find->name = $r->name ?? $find->name;
            $find->price = $r->price ?? $find->price;
            $find->category_id = $r->category_id ?? $find->category_id;
            $find->qty = $r->qty ?? $find->qty;
            $find->description = $r->description ?? $find->description;
            if ($r->hasFile('image')) {
                $find->image = $r->image->store('images', 'custom');
            }
            $find->save();
            return response()->json($find, 200);
        }
        return response()->json(null, 404);
    }

    public function delete($id){
        $find = Product::find($id);
        if($find){
            $find->delete();
            return response()->json(null, 204);
        }
        return response()->json(null, 404);
    }
}
