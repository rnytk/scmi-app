<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Packages\PackageDashboard;
use App\Livewire\Packages\CreatePackage;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::prefix('packages')->name('packages.')->group(function () {
        // Rutas apuntando a las clases de Livewire
        Route::get('/', PackageDashboard::class)->name('index');
        Route::get('/reception', PackageDashboard::class)->name('reception');
        Route::get('/create', CreatePackage::class)->name('create');
        Route::get('/route', PackageDashboard::class)->name('route');
    });
});

require __DIR__.'/settings.php';
