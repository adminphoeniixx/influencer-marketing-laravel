<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Mongo\CreatorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CreatorDiscoveryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->integer('per_page', 12);
        $perPage = max(1, min($perPage, 48)); // safety
        $page    = (int) $request->integer('page', 1);
        $page    = max(1, $page);

        // Filters
        $platform     = $request->string('platform')->toString();   // instagram|youtube|twitter
        $category     = $request->string('category')->toString();   // Tech
        $location     = $request->string('location')->toString();   // Mumbai
        $minFollowers = $request->integer('min_followers', null);
        $maxFollowers = $request->integer('max_followers', null);

        // Pricing filters: we’ll filter on one “primary” field for now (post),
        // later you can add price_type=post|reel|story.
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');

        $sort = $request->string('sort', 'newest')->toString(); // newest | followers_desc

        // ✅ Redis cache key (unique per filter combo + page)
        $cacheKey = 'discovery:creators:' . md5(json_encode([
            'platform' => $platform ?: null,
            'category' => $category ?: null,
            'location' => $location ?: null,
            'min_followers' => $minFollowers,
            'max_followers' => $maxFollowers,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'sort' => $sort,
            'page' => $page,
            'per_page' => $perPage,
        ]));

        // Cache TTL (tune later)
        $ttlSeconds = 60;

        $payload = Cache::remember($cacheKey, $ttlSeconds, function () use (
            $platform, $category, $location,
            $minFollowers, $maxFollowers,
            $minPrice, $maxPrice,
            $sort, $page, $perPage
        ) {
            $query = CreatorProfile::query()
                ->where('status', 'approved');

            // Platform filter (array of objects)
            if ($platform) {
                $query->where('platforms', 'elemMatch', [
                    'platform' => $platform,
                ]);
            }

            // Category filter (array of strings)
            if ($category) {
                $query->where('categories', $category);
            }

            // Location filter
            if ($location) {
                $query->where('location', $location);
            }

            // Followers filter (we’ll use max followers among platforms for now)
            // For performance & correctness at scale, later store a denormalized field like: followers_max
            if ($minFollowers !== null) {
                $query->where('platforms.followers', '>=', (int) $minFollowers);
            }
            if ($maxFollowers !== null) {
                $query->where('platforms.followers', '<=', (int) $maxFollowers);
            }

            // Price filter (pricing.post)
            if ($minPrice !== null && is_numeric($minPrice)) {
                $query->where('pricing.post', '>=', (float) $minPrice);
            }
            if ($maxPrice !== null && is_numeric($maxPrice)) {
                $query->where('pricing.post', '<=', (float) $maxPrice);
            }

            // Sorting
            if ($sort === 'followers_desc') {
                // NOTE: Sorting by nested array field is tricky.
                // For best performance later add a denormalized field: followers_max and sort on that.
                // For now we sort by created_at desc as safe fallback if Mongo can’t sort reliably.
                $query->orderBy('created_at', 'desc');
            } else {
                $query->orderBy('created_at', 'desc');
            }

            // Pagination (Mongo skip/limit)
            $total = (clone $query)->count();

            $items = $query
                ->skip(($page - 1) * $perPage)
                ->take($perPage)
                ->get([
                    'user_id',
                    'display_name',
                    'bio',
                    'platforms',
                    'categories',
                    'languages',
                    'pricing',
                    'location',
                    'verified_at',
                    'created_at',
                ]);

            return [
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'items' => $items,
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Creators fetched successfully.',
            'data' => $payload,
        ]);
    }
}