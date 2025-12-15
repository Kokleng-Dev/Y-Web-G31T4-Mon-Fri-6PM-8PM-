<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::get('/test', function (Request $request) {
//     dd($request->header("Accept"));
//     // return "hello";
// });
// Route::get("/test", function(){
//     return response()->json([
//         "status" => "success"], 500);
// });


Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register'])->name('register');
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login'])->name('login');



Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout'])->name('logout');
    Route::get("/product", [App\Http\Controllers\Api\ProductController::class, 'index'])->name('product.index');

    //users
    Route::get('/user', [App\Http\Controllers\Api\UserController::class, 'index'])
            ->middleware('permission:user,list');
    Route::post('/user', [App\Http\Controllers\Api\UserController::class, 'store'])
        ->middleware('permission:user,create');
    Route::get('/user/{id}',[App\Http\Controllers\Api\UserController::class, 'get'])
        ->middleware('permission:user,edit');
    Route::put('/user/{id}', [App\Http\Controllers\Api\UserController::class, 'update'])
        ->middleware('permission:user,edit');

    Route::delete('/user/{id}', [App\Http\Controllers\Api\UserController::class, 'delete'])
        ->middleware('permission:user,delete');

    // role
    Route::get('/role', [App\Http\Controllers\Api\RoleController::class, 'index'])
        ->middleware('permission:role,list');

    Route::post('/role', [App\Http\Controllers\Api\RoleController::class, 'create'])
        ->middleware('permission:role,create');
    Route::get('/role/{id}', [App\Http\Controllers\Api\RoleController::class, 'detail'])
        ->middleware('permission:role,edit');

    Route::put('/role/{id}', [App\Http\Controllers\Api\RoleController::class, 'update'])
        ->middleware('permission:role,edit');
    Route::delete('/role/{id}', [App\Http\Controllers\Api\RoleController::class, 'delete'])
        ->middleware('permission:role,delete');

    // Permission
    Route::get('/permission', [App\Http\Controllers\Api\PermissionController::class, 'index']);
    Route::post('/permission', [App\Http\Controllers\Api\PermissionController::class, 'create']);
    Route::get('/permission/{id}', [App\Http\Controllers\Api\PermissionController::class, 'detail']);
    Route::put('/permission/{id}', [App\Http\Controllers\Api\PermissionController::class, 'update']);
    Route::delete('/permission/{id}', [App\Http\Controllers\Api\PermissionController::class, 'delete']);

    // Permission Feature
    Route::get('/permission-feature', [App\Http\Controllers\Api\PermissionFeatureController::class, 'index']);
    Route::post('/permission-feature', [App\Http\Controllers\Api\PermissionFeatureController::class, 'create']);
    Route::get('/permission-feature/{id}', [App\Http\Controllers\Api\PermissionFeatureController::class, 'detail']);
    Route::put('/permission-feature/{id}', [App\Http\Controllers\Api\PermissionFeatureController::class, 'update']);
    Route::delete('/permission-feature/{id}', [App\Http\Controllers\Api\PermissionFeatureController::class, 'delete']);

    // Role Permission
    Route::get('/role-permission/{role_id}', [App\Http\Controllers\Api\RolePermissionController::class, 'index']);
    Route::post('/set-permission', [App\Http\Controllers\Api\RolePermissionController::class, 'set_permission']);

    // Customer
    Route::get('/customer', [App\Http\Controllers\Api\CustomerController::class, 'index']);
    Route::post('/customer', [App\Http\Controllers\Api\CustomerController::class, 'create']);
    Route::get('/customer/{id}', [App\Http\Controllers\Api\CustomerController::class, 'detail']);
    Route::put('/customer/{id}', [App\Http\Controllers\Api\CustomerController::class, 'update']);
    Route::delete('/customer/{id}', [App\Http\Controllers\Api\CustomerController::class, 'delete']);

    // Category
    Route::get('/category', [App\Http\Controllers\Api\CategoryController::class, 'index']);
    Route::post('/category', [App\Http\Controllers\Api\CategoryController::class, 'create']);
    Route::get('/category/{id}', [App\Http\Controllers\Api\CategoryController::class, 'detail']);
    Route::put('/category/{id}', [App\Http\Controllers\Api\CategoryController::class, 'update']);
    Route::delete('/category/{id}', [App\Http\Controllers\Api\CategoryController::class, 'delete']);

    // Product
    Route::get('/product', [App\Http\Controllers\Api\ProductController::class, 'index']);
    Route::post('/product', [App\Http\Controllers\Api\ProductController::class, 'create']);
    Route::get('/product/{id}', [App\Http\Controllers\Api\ProductController::class, 'detail']);
    Route::post('/product/{id}', [App\Http\Controllers\Api\ProductController::class, 'update']);
    Route::delete('/product/{id}', [App\Http\Controllers\Api\ProductController::class, 'delete']);

    // Order
    Route::get('/order',[App\Http\Controllers\Api\OrderController::class, 'index']);
    Route::post('/order', [App\Http\Controllers\Api\OrderController::class, 'create']);
    Route::delete('/order/void/{id}', [App\Http\Controllers\Api\OrderController::class, 'delete']);
});


// Route::get('/test', function(){
//     return checkPermission(1,"user","create");
// });
