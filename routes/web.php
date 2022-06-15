<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('/home/blog/post', App\Http\Controllers\PostController::class);
    Route::put('/home/blog/post/update_stat/{id}', [App\Http\Controllers\PostController::class,'update_stat'])->name('post.update_stat');
    Route::post('/home/blog/post/store_on_home', [App\Http\Controllers\PostController::class,'store_on_home'])->name('post.store_on_home');
    Route::resource('/home/blog/category', App\Http\Controllers\CategoryController::class);
});