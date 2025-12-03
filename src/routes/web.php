<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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
Route::get('/item/{item_id}',[ItemController::class,"detail"]);


Route::middleware(['auth','verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'create'])->name('profile.create');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store');
    Route::get('/mypage',[ProfileController::class,'index'])->name('mypage');
    Route::get('/mypage/profile',[ProfileController::class,'edit'])->name('profile.edit');
    Route::patch('mypage/profile',[ProfileController::class,'update'])->name('profile.update');
    Route::get('/sell',[ItemController::class,'index']);
    Route::post('sell',[ItemController::class,'sell']);
    Route::post('/item/{item}/comment',[ItemController::class,'store']);
    Route::get('/purchase/{item_id}',[ItemController::class,'show'])->name('purchase');
    Route::get('/purchase/address/{item_id}',[ItemController::class,'edit']);
    Route::post('/purchase/address/{item_id}',[ItemController::class,'update']);
    Route::post('/purchase/{item_id}',[ItemController::class,'purchase']);
    Route::post('favorite/{item_id}',[ItemController::class,'toggle']);
});


// ログイン済みだがメール未認証
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.email');
    })->name('verification.notice');
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back();
    })->middleware('throttle:6,1')->name('verification.send');
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->to(route('profile.create'));
    })->middleware('auth', 'signed')->name('verification.verify');
});

