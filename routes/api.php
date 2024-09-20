<?php

use App\Http\Controllers\AnalyticController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('/')->middleware(['auth:sanctum'])->group(function(){
    Route::get('user', function (Request $request) {
        return $request->user();
    });
    Route::get('inventories/search',[InventoryController::class,'search']);
    Route::resource('inventories', InventoryController::class);
    Route::get('categories/search',[CategoryController::class,'search']);
    Route::resource('categories',CategoryController::class);
    Route::get('mainsuppliersdata',[SupplierController::class,'mainSupplierData']);
    Route::get('suppliers/search', [SupplierController::class,'search']);
    Route::apiResource('suppliers', SupplierController::class);
    Route::get('/orders/search',[OrderController::class,'search']);
    Route::apiResource('orders', OrderController::class);
    Route::get('maincategories',[CategoryController::class,'mainCategories']);
    Route::apiResource('settings/users', UserController::class);
    Route::get('analytics',[AnalyticController::class,'index']);
});

Route::post('login', function (Request $request) {
    $user = User::where('email',$request->email)->first();
    if($user && Hash::check($request->password,$user->password)){
        $token = $user->createToken('appToken')->plainTextToken;
        return response()->json([
            'user'=>$user,
            'token'=>$token,
        ]);
    }
    return response()->json([
        'error'=>'email or password is incorrect!',
    ]);
});
