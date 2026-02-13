<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\CreatorApprovalController;

Route::middleware(['auth', 'role:admin|sub_admin'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/dashboard', function () {
            return Inertia::render('Admin/Dashboard');
        })->name('admin.dashboard');

        Route::get('/creators/pending', [CreatorApprovalController::class, 'pending'])
            ->name('admin.creators.pending');

        Route::post('/creators/{userId}/approve', [CreatorApprovalController::class, 'approve'])
            ->name('admin.creators.approve');

        Route::post('/creators/{userId}/reject', [CreatorApprovalController::class, 'reject'])
            ->name('admin.creators.reject');
    });