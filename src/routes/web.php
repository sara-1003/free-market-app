<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;

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

Route::get('/',[AuthController::class,'index']);
Route::get('items/search', [AuthController::class, 'search'])->name('items.search');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'create'])->name('profile.create');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store');
    Route::get('/mypage',[ProfileController::class,'index'])->name('mypage');
    Route::get('/mypage/profile',[ProfileController::class,'edit'])->name('profile.edit');
    Route::patch('mypage/profile',[ProfileController::class,'update'])->name('profile.update');
    Route::get('/sell',[ItemController::class,'index']);
    Route::post('sell',[ItemController::class,'sell']);
});


