<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class ProductController extends Controller
{
    //
    public function index(Request $r){
        return response()->json([
            "from_user" => auth()->user(),
            'data' => [
                (object)[
                    'id' => 1,
                    'name' => 'Product 1'
                ],
                (object)[
                    'id' => 2,
                    'name' => 'Product 2'
                ]
                ],
        ]);
    }
}
