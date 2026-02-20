<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Creator\CreatorProfileController;
use App\Http\Controllers\App\CreatorDiscoveryController;
use App\Http\Controllers\Creator\CreatorDashboardController;
use App\Http\Controllers\App\BrandDashboardController;
/**
 * Role-based "app dashboard"
 */




Route::middleware(['auth', 'role:brand'])
    ->prefix('app/brand')
    ->group(function () {

        Route::get('/dashboard-view',
            [BrandDashboardController::class, 'index']
        )->name('brand.dashboard');

});

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


/** Creator dashboard controller */
Route::middleware(['auth', 'role:creator'])->group(function () {

    Route::get('/app/creator/dashboard',
        [CreatorDashboardController::class, 'index']
    )->name('creator.dashboard.controller');

});


/** Creator dashboard view */
Route::middleware(['auth', 'role:creator'])->group(function () {

    Route::get('/app/creator/dashboard-view', function () {

        return Inertia::render('Creator/Dashboard');

    })->name('creator.dashboard');

});


/** Brand dashboard view */
Route::middleware(['auth', 'role:brand'])->group(function () {

    Route::get('/app/brand/dashboard-view', function () {

        return Inertia::render('Brand/Dashboard');

    })->name('brand.dashboard');

});


/** Creator profile view */
Route::middleware(['auth', 'role:creator'])->group(function () {

    Route::get('/app/creator/profile-view', function () {

        return Inertia::render('Creator/ProfileForm');

    })->name('creator.profile.view');

});


/** Creator profile API */
Route::middleware(['auth', 'role:creator'])->group(function () {

    Route::get('/creator/profile',
        [CreatorProfileController::class, 'show']
    )->name('creator.profile.show');


    Route::post('/creator/profile',
        [CreatorProfileController::class, 'store']
    )->name('creator.profile.store');

});


/** Creator discovery API */
Route::middleware(['auth'])->group(function () {

    Route::get('/app/creators',
        [CreatorDiscoveryController::class, 'index']
    )->name('app.creators.index');

});



Route::middleware(['auth','role:brand'])->group(function () {

   

    Route::get('/app/creators/{id}', [CreatorDiscoveryController::class, 'show'])
        ->name('brand.creators.show');

});


/** ✅ Brand browse creators view */
Route::middleware(['auth', 'role:brand'])->group(function () {

    Route::get('/app/creators-view',
        [CreatorDiscoveryController::class, 'index']
    )->name('brand.creators.browse');

});