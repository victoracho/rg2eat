<?php

use App\Http\Controllers\Admin\BusinessHourController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuCategoryController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PublicSiteController;
use Illuminate\Support\Facades\Route;

// ──────────────── PUBLIC ────────────────
Route::get('/',     [PublicSiteController::class, 'home'])->name('home');
Route::get('/menu', [PublicSiteController::class, 'menu'])->name('menu');
Route::get('/qr/menu.png', [PublicSiteController::class, 'menuQr'])->name('menu.qr');

// ──────────────── AUTH ────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',  [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ──────────────── ADMIN ────────────────
Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('settings', SiteSettingController::class)
            ->only(['index', 'edit', 'update']);

        Route::resource('hours', BusinessHourController::class)
            ->only(['index', 'update', 'store', 'destroy']);

        Route::resource('categories', MenuCategoryController::class);
        Route::resource('items',      MenuItemController::class);
    });
