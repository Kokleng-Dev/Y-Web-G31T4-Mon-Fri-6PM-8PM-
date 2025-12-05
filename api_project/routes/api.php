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
    Route::get('/user', [App\Http\Controllers\Api\UserController::class, 'index'])->name('users.index');
    Route::post('/user', [App\Http\Controllers\Api\UserController::class, 'store'])->name('users.store');
    Route::get('/user/{id}',[App\Http\Controllers\Api\UserController::class, 'get'])->name('users.get');
    Route::put('/user/{id}', [App\Http\Controllers\Api\UserController::class, 'update'])->name('users.update');
    Route::delete('/user/{id}', [App\Http\Controllers\Api\UserController::class, 'delete'])->name('users.delete');

    // role
    Route::get('/role', [App\Http\Controllers\Api\RoleController::class, 'index']);
    Route::post('/role', [App\Http\Controllers\Api\RoleController::class, 'create']);
    Route::get('/role/{id}', [App\Http\Controllers\Api\RoleController::class, 'detail']);
    Route::put('/role/{id}', [App\Http\Controllers\Api\RoleController::class, 'update']);
    Route::delete('/role/{id}', [App\Http\Controllers\Api\RoleController::class, 'delete']);

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
});


