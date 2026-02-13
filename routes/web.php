<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

require __DIR__ . '/admin.php';
require __DIR__ . '/app.php';

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
});

/**
 * ✅ Secure /dashboard (Jetstream default) by turning it into a role-based redirect.
 * This prevents creators/brands from ever seeing admin pages.
 */
Route::middleware(['auth'])->get('/dashboard', function () {
    $user = auth()->user();

    if ($user->hasAnyRole(['admin', 'sub_admin'])) {
        return redirect('/admin/dashboard');
    }

    if ($user->hasRole('creator')) {
        return redirect('/app/creator/dashboard-view');
    }

    if ($user->hasRole('brand')) {
        return redirect('/app/brand/dashboard-view');
    }

    abort(403);
})->name('dashboard');

/**
 * ✅ Post-login redirect target (set this in RouteServiceProvider::HOME)
 */
Route::middleware(['auth'])->get('/redirect-after-login', function () {
    $user = auth()->user();

    if ($user->hasAnyRole(['admin', 'sub_admin'])) {
        return redirect('/admin/dashboard');
    }

    if ($user->hasRole('creator')) {
        return redirect('/app/creator/dashboard-view');
    }

    if ($user->hasRole('brand')) {
        return redirect('/app/brand/dashboard-view');
    }

    abort(403);
})->name('redirect.after.login');