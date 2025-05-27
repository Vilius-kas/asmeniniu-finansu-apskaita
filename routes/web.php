<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlowController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
Route::middleware(['auth'])->group(function () {
Route::post('/flows', [FlowController::class, 'index'])->name('flows.index');
Route::get('/flows', [FlowController::class, 'index'])->name('flows.index');
Route::get('/flows/create', [FlowController::class, 'create'])->name('flows.create');
Route::post('/flows', [FlowController::class, 'store'])->name('flows.store');

Route::get('/flows/{flow}/edit', [FlowController::class, 'edit'])->name('flows.edit');
Route::put('/flows/{flow}', [FlowController::class, 'update'])->name('flows.update');
});