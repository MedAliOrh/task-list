<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
});


require __DIR__.'/auth.php';

Route::get('/register', function () {
	return view('register');
})->name('register');

Route::get('/login', function () {
	return view('login');
})->name('login');

Route::middleware(['auth'])->group(function () {
	Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
	Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
	Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
	Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});

