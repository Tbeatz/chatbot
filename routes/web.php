<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //files
    Route::get('/file/{path}', function ($path) {
        if (Storage::path('upload/'.$path)) {
            return response()->file(Storage::path('upload/'.$path));
        } else {
            abort(404, 'File Not Found');
        }
    })->name('file')->where('path', '.*');

    Route::get('/dashboard', [ChatController::class, 'index'])->middleware('verified')->name('dashboard');
    Route::post('/chat', [ChatController::class, 'chat'])->name('send_msg');
    Route::delete('/delete', [ChatController::class, 'delete_topic'])->name('del_topic');
    Route::patch('/update', [ChatController::class, 'update_topic'])->name('update_topic');
});


require __DIR__.'/auth.php';
