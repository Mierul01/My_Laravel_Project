<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NewsletterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Newsletter CRUD Routes
    Route::resource('newsletters', NewsletterController::class);

    // Route to restore deleted newsletters
    Route::get('newsletters/restore/{id}', [NewsletterController::class, 'restore'])->name('newsletters.restore');

    // API route to fetch newsletters
    Route::get('/api/newsletters', [NewsletterController::class, 'apiIndex'])->name('api.newsletters.index');

    // Route to hide (soft delete) newsletters
    Route::post('/newsletters/hide/{id}', [NewsletterController::class, 'hide'])->name('newsletters.hide');

    // Route to force delete newsletters
    Route::delete('newsletters/force-delete/{id}', [NewsletterController::class, 'forceDelete'])->name('newsletters.forceDelete');
});

// Public routes for viewing newsletters
Route::get('/user/newsletters', [NewsletterController::class, 'userIndex'])->name('user.newsletters.index');
Route::get('/user/newsletters/{newsletter}', [NewsletterController::class, 'userShow'])->name('user.newsletters.show');

require __DIR__.'/auth.php';



