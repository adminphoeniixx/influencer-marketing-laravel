<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mongo\CreatorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CreatorApprovalController extends Controller
{
    /**
     * List pending creators
     */
    public function pending()
    {
        $creators = CreatorProfile::pending()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data'   => $creators,
        ]);
    }

    /**
     * Approve creator
     */
    public function approve($userId)
    {
        $profile = CreatorProfile::where('user_id', (int) $userId)->firstOrFail();

        $profile->update([
            'status'      => 'approved',
            'verified_at' => now(),
        ]);

        // 🔥 Redis refresh
        Cache::forget('creators:list:approved');
        Cache::forget("creator:{$userId}");

        return response()->json([
            'status'  => true,
            'message' => 'Creator approved successfully.',
        ]);
    }

    /**
     * Reject creator
     */
    public function reject(Request $request, $userId)
    {
        $profile = CreatorProfile::where('user_id', (int) $userId)->firstOrFail();

        $profile->update([
            'status'      => 'rejected',
            'verified_at' => null,
        ]);

        // 🔥 Redis refresh
        Cache::forget('creators:list:approved');
        Cache::forget("creator:{$userId}");

        return response()->json([
            'status'  => true,
            'message' => 'Creator rejected.',
        ]);
    }
}