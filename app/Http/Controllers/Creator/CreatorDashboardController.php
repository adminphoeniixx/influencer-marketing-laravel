<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\Mongo\CreatorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CreatorDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // 🔥 Try Redis first
        $cacheKey = "creator:dashboard:{$user->id}";

        $data = Cache::remember($cacheKey, 60, function () use ($user) {

            $profile = CreatorProfile::where('user_id', $user->id)->first();

            if (!$profile) {
                return [
                    'has_profile' => false,
                    'status'      => null,
                    'profile'     => null,
                    'stats'       => [],
                ];
            }

            return [
                'has_profile' => true,
                'status'      => $profile->status,
                'verified'    => $profile->status === 'approved',
                'profile' => [
                    'display_name' => $profile->display_name,
                    'bio'          => $profile->bio,
                    'platforms'    => $profile->platforms,
                    'categories'   => $profile->categories,
                    'location'     => $profile->location,
                ],
                // 🔢 Placeholder stats (we’ll wire real ones later)
                'stats' => [
                    'profile_views' => 0,
                    'shortlisted'   => 0,
                    'campaigns'     => 0,
                ],
            ];
        });

        return response()->json([
            'status' => true,
            'data'   => $data,
        ]);
    }
}