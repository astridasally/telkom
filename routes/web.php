<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;
use App\Exports\ProjectsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [ProjectController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/funneling-olt', [ProjectController::class, 'funnelingOltReport'])
    ->name('funneling_olt_report');

// ini di luar middleware supaya bisa diakses AJAX
Route::get('/witels/{regional}', [ProjectController::class, 'getWitels'])
    ->where('regional', '.*');

Route::middleware('auth')->group(function () {    
    Route::get('/form',  [ProjectController::class, 'projectForm'])->name('project_create');
    Route::post('/form', [ProjectController::class, 'store_projectForm'])->name('project_store');

    Route::get('/formta',  [ProjectController::class, 'projectFormTA'])->name('project_create_ta');
    Route::post('/formta', [ProjectController::class, 'store_projectFormTA'])->name('project_store_ta');
    Route::get('/report',  [ProjectController::class, 'report'])->name('project_report');

    Route::get('/form-update/{id}', [ProjectController::class, 'show_project'])->name('project_update');
    Route::patch('/form-update/{id}', [ProjectController::class, 'store_project_update'])->name('project_store');
    Route::patch('/form-update/{id}', [ProjectController::class, 'store_project_update_admin'])->name('project_store_admin');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/projects/import', function () {
        return view('import');
    });

    Route::post('/projects/import', [ProjectController::class, 'import'])->name('projects.import');

    Route::get('/popup-detail', [ProjectController::class, 'getPopupDetail'])->name('popup.detail');


    //download REPORT
    Route::get('/download-projects', function (Request $request) {
    $type = $request->get('project_type'); // 'Project TA' atau 'Project Mitratel'
    return Excel::download(new ProjectsExport($type), 'projects.xlsx');
        })->name('download_projects');

    Route::post('/dashboard/export', [ProjectController::class, 'exportFunneling'])->name('funneling.export');
        Route::post('/popup/export', [ProjectController::class, 'exportPopup'])->name('popup.export');


});

require __DIR__ . '/auth.php';
