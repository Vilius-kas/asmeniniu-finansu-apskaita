<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\FlowController;
use App\Http\Controllers\ReportsController;

Route::get('/', function () {
    return redirect('/login');
});
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

    // .
    Route::get('/categories/type/{type}', [CategoryController::class, 'getByType']);
    Route::get('/subcategories/category/{categoryId}', [SubcategoryController::class, 'getByCategory']);
    Route::get('/categories/type/{type}', function($type) {
    $categories = App\Models\Category::where('type', $type)->get();
    return response()->json($categories);
    });

    Route::get('/subcategories/category/{categoryId}', function($categoryId) {
    $subcategories = App\Models\Subcategory::where('category_id', $categoryId)->get();
    return response()->json($subcategories);
});
});
