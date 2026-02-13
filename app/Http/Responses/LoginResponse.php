<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        if ($user->hasRole('admin') || $user->hasRole('sub_admin')) {
            return redirect('/admin/dashboard');
        }

        if ($user->hasRole('creator')) {
            return redirect('/app/creator/dashboard-view');
        }

        if ($user->hasRole('brand')) {
            return redirect('/app/brand/dashboard');
        }

        return redirect('/dashboard'); // fallback
    }
}