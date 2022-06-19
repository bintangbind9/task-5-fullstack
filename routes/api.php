<?php

use App\Helpers\Constant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function () {
    Route::post('/register', [App\Http\Controllers\API\Auth\RegisterController::class, 'register'])->name('api.register');
    Route::post('/login', [App\Http\Controllers\API\Auth\RegisterController::class, 'login'])->name('api.login');
});

Route::prefix('v1')->middleware(['auth:api', 'verified', 'role:'.Constant::ROLE_ADMIN])->group(function () {
    Route::resource('/category', App\Http\Controllers\API\CategoryController::class);
});

Route::prefix('v1')->middleware(['auth:api', 'verified'])->group(function () {
    Route::resource('/post', App\Http\Controllers\API\PostController::class);
});