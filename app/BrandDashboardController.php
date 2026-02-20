<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Mongo\CreatorProfile;
use Inertia\Inertia;

class BrandDashboardController extends Controller
{
    public function index()
    {
        $creators = CreatorProfile::where('status', 'approved')
            ->limit(6)
            ->get()
            ->map(function ($c) {
                return [
                    '_id' => (string) $c->_id,
                    'display_name' => $c->display_name,
                    'platforms' => $c->platforms ?? [],
                    'pricing' => $c->pricing ?? [],
                    'location' => $c->location,
                ];
            });

        return Inertia::render('Brand/Dashboard', [
            'creators' => $creators,
            'stats' => [
                'total_creators' => CreatorProfile::where('status','approved')->count(),
                'campaigns' => 0,
                'active_deals' => 0,
            ]
        ]);
    }
}