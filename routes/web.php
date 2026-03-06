<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/post/create',[PostController::class,'create'])->name('posts.create');
Route::post('/post/store',[PostController::class,'store'])->name('posts.store');

Route::get('/', function () {
    return view('welcome');
});
