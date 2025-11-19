<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.dashboard');
});

Route::get('/dashboard', function () {
    return view('admin.dashboard');
});

Route::get('/admins', [AdminController::class, 'getDataAdmin'])->name('admin.index');
Route::post('/admins/store', [AdminController::class, 'store'])->name('admin.store');
Route::get('/admins/delete/all', [AdminController::class, 'destroyAll'])->name('admin.deleteAll');
Route::get('/admins/delete/{id}', [AdminController::class, 'destroy'])->name('admin.delete');
