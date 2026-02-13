<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Creator\CreatorProfileController;
use App\Http\Controllers\App\CreatorDiscoveryController;
use App\Http\Controllers\Creator\CreatorDashboardController;

/**
 * Role-based "app dashboard" entry.
 * We keep /app/dashboard as a redirect so sidebar can point there if needed,
 * but your creator sidebar should ideally point to /app/creator/dashboard-view.
 */
Route::middleware(['auth'])->prefix('app')->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->hasRole('creator')) {
            return redirect('/app/creator/dashboard-view');
        }

        if ($user->hasRole('brand')) {
            return redirect('/app/brand/dashboard-view');
        }

        abort(403);
    })->name('app.dashboard');
});

/** ───────── Creator dashboard (controller version) ───────── */
Route::middleware(['auth', 'role:creator'])->group(function () {
    Route::get('/app/creator/dashboard', [CreatorDashboardController::class, 'index'])
        ->name('creator.dashboard.controller');
});

/** ───────── Creator dashboard (Inertia view) ───────── */
Route::middleware(['auth', 'role:creator'])->group(function () {
    Route::get('/app/creator/dashboard-view', function () {
        return Inertia::render('Creator/Dashboard');
    })->name('creator.dashboard');
});

/** ───────── Brand dashboard (Inertia view) ───────── */
Route::middleware(['auth', 'role:brand'])->group(function () {
    Route::get('/app/brand/dashboard-view', function () {
        return Inertia::render('Brand/Dashboard');
    })->name('brand.dashboard');
});

/** ───────── Creator profile page (Inertia view) ───────── */
Route::middleware(['auth', 'role:creator'])->group(function () {
    Route::get('/app/creator/profile-view', function () {
        return Inertia::render('Creator/ProfileForm');
    })->name('creator.profile.view');
});

/** ───────── Creator profile API (axios GET/POST) ───────── */
Route::middleware(['auth', 'role:creator'])->group(function () {
    Route::get('/creator/profile', [CreatorProfileController::class, 'show'])->name('creator.profile.show');
    Route::post('/creator/profile', [CreatorProfileController::class, 'store'])->name('creator.profile.store');
});

/** ───────── Creator discovery (brands can browse; you can keep it auth-only) ───────── */
Route::middleware(['auth'])->group(function () {
    Route::get('/app/creators', [CreatorDiscoveryController::class, 'index'])
        ->name('app.creators.index');
});