<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/form',  [ProjectController::class, 'projectForm'])->name('project_create');
    Route::post('/form', [ProjectController::class, 'store_projectForm'])->name('project_store');
    Route::get('/report',  [ProjectController::class, 'report'])->name('project_report');

    Route::get('/formta',  [ProjectController::class, 'projectFormTA'])->name('project_create_ta');
    Route::post('/formta', [ProjectController::class, 'store_projectFormTA'])->name('project_store_ta');

    
    Route::get('/form-update/{id}', [ProjectController::class, 'show_project'])->name('project_update');
    Route::patch('/form-update/{id}', [ProjectController::class, 'store_project_update'])->name('project_store');

    Route::patch('/form-update/{id}', [ProjectController::class, 'store_project_update_admin'])->name('project_store_admin');




    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';