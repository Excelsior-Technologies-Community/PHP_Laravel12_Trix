<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', [PostController::class, 'index'])->name('posts.index');

Route::get('/post/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/post/store', [PostController::class, 'store'])->name('posts.store');

Route::delete('/post/{id}', [PostController::class, 'destroy'])->name('posts.delete');
Route::get('/post/{id}', [PostController::class, 'show'])->name('posts.show');

Route::get('/post/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/post/{id}', [PostController::class, 'update'])->name('posts.update');