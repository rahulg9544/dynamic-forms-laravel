<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    // Form management routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('forms', FormController::class);
    });
});

Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login']);




//public

// Route to display the list of forms
Route::get('/forms', [FormController::class, 'public_index'])->name('public.forms.index');

// Route to display the form
Route::get('/forms/{id}', [FormController::class, 'show'])->name('public.forms.show');

// Route to handle form submissions
Route::post('/forms/{id}/submit', [FormController::class, 'submit'])->name('public.forms.submit');