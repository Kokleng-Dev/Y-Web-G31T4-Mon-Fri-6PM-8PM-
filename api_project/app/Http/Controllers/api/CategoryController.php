<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $r){
        $per_page = $r->per_page ?? 10;
        $data = Category::paginate($per_page);
        return response()->json($data, 200);
    }

    public function create(Request $r){
        $validator = Validator::make($r->all(), [
            'name' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        $instand = new Category();
        $instand->name = $r->name;
        $instand->save();
        return response()->json($instand, 201);
    }

    public function detail($id){
        $find = Category::find($id);
        if($find){
            return response()->json($find, 200);
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
        $find = Category::find($id);
        if($find){
            $find->name = $r->name;
            $find->save();
            return response()->json($find, 200);
        }
        return response()->json(null, 404);
    }

    public function delete($id){
        $find = Category::find($id);
        if($find){
            $find->delete();
            return response()->json(null, 204);
        }
        return response()->json(null, 404);
    }
}
