<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth'])->group(static function (): void {
    Route::prefix('catalogues')->as('catalogues:')->group(base_path('routes/catalogues.php'));
});

require __DIR__.'/auth.php';
