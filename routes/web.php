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

Route::get('/flows/create', [FlowController::class, 'create'])->name('flows.create');
Route::post('/flows', [FlowController::class, 'store'])->name('flows.store');