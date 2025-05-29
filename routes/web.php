<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\FlowController;
use App\Http\Controllers\ReportsController;

Route::middleware(['auth'])->group(function () {
    // Kategorijos
    Route::resource('categories', CategoryController::class);
    
    // Subkategorijos
    Route::resource('subcategories', SubcategoryController::class);
    Route::get('subcategories/type/{type}', [SubcategoryController::class, 'getByType']);
    
    // Srautai
    Route::resource('flows', FlowController::class);
    
    // Ataskaitos
    Route::get('reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::post('reports/generate', [ReportsController::class, 'generate'])->name('reports.generate');
    
    // Pagrindinis puslapis
    Route::get('/dashboard', [FlowController::class, 'index'])->name('dashboard');
});
