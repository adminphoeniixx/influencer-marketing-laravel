<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\Mongo\CreatorProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CreatorProfileController extends Controller
{
    /**
     * These are the ONLY service keys allowed per platform.
     * Keep this in sync with your frontend map.
     */
    private const PLATFORM_SERVICES = [
        'instagram' => ['post', 'story', 'reel'],
        'youtube'   => ['short', 'video', 'community'],
        'twitter'   => ['tweet', 'thread'],
    ];

    public function show(Request $request)
    {
        $profile = CreatorProfile::where('user_id', (int) $request->user()->id)->first();

        return response()->json([
            'status' => true,
            'data'   => $profile,
        ]);
    }

    public function store(Request $request)
    {
        $userId = (int) $request->user()->id;

        $v = Validator::make($request->all(), [
            'display_name' => ['required', 'string', 'max:120'],
            'bio'          => ['nullable', 'string', 'max:2000'],
            'location'     => ['nullable', 'string', 'max:120'],

            'categories'   => ['nullable', 'array', 'max:20'],
            'categories.*' => ['string', 'max:50'],

            'languages'    => ['nullable', 'array', 'max:20'],
            'languages.*'  => ['string', 'max:50'],

            'platforms'                => ['required', 'array', 'min:1', 'max:10'],
            'platforms.*.platform'     => ['required', 'string', 'in:instagram,youtube,twitter'],
            'platforms.*.username'     => ['required', 'string', 'max:100'],
            'platforms.*.followers'    => ['required', 'integer', 'min:0', 'max:2000000000'],
            'platforms.*.engagement'   => ['nullable', 'numeric', 'min:0', 'max:100'],

            'platforms.*.services'     => ['required', 'array'],
            // individual service keys validated in normalize() below
        ], [
            'platforms.required' => 'Please add at least one platform.',
        ]);

        if ($v->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $v->errors()->first(),
                'errors'  => $v->errors(),
            ], 422);
        }

        // Normalize & validate service keys (platform-specific)
        $payload = $this->normalizePayload($request->all());

        if ($payload['__error'] ?? false) {
            return response()->json([
                'status'  => false,
                'message' => $payload['__message'] ?? 'Invalid services data.',
            ], 422);
        }

        unset($payload['__error'], $payload['__message']);

        // Create or update
        $profile = CreatorProfile::firstOrNew(['user_id' => $userId]);

        // If updating an already approved/rejected profile, set back to pending on changes
        // (You can later implement "draft" if you want)
        $isNew = !$profile->exists;

        $profile->fill([
            'user_id'       => $userId,
            'display_name'  => $payload['display_name'],
            'bio'           => $payload['bio'],
            'location'      => $payload['location'],
            'categories'    => $payload['categories'],
            'languages'     => $payload['languages'],
            'platforms'     => $payload['platforms'],

            // Workflow
            'status'        => 'pending',
            'submitted_at'  => Carbon::now('Asia/Kolkata'),
            'rejection_reason' => null,
            'approved_at'   => null,
            'rejected_at'   => null,
        ]);

        $profile->save();

        // (Optional later) Redis cache invalidation here:
        // Cache::tags(['creators'])->flush();

        return response()->json([
            'status'  => true,
            'message' => $isNew ? 'Profile submitted for review.' : 'Profile updated and submitted for review.',
            'data'    => $profile,
        ]);
    }

    /**
     * Normalizes payload and enforces platform-specific service keys.
     * Also forces non-negative numeric prices.
     */
    private function normalizePayload(array $data): array
    {
        $out = [
            'display_name' => trim((string) ($data['display_name'] ?? '')),
            'bio'          => isset($data['bio']) ? trim((string) $data['bio']) : null,
            'location'     => isset($data['location']) ? trim((string) $data['location']) : null,
            'categories'   => array_values(array_filter((array) ($data['categories'] ?? []))),
            'languages'    => array_values(array_filter((array) ($data['languages'] ?? []))),
            'platforms'    => [],
        ];

        $platforms = (array) ($data['platforms'] ?? []);

        foreach ($platforms as $p) {
            $platform = (string) ($p['platform'] ?? '');
            if (!isset(self::PLATFORM_SERVICES[$platform])) {
                return ['__error' => true, '__message' => "Invalid platform: {$platform}"];
            }

            $allowed = self::PLATFORM_SERVICES[$platform];

            $servicesIn = (array) ($p['services'] ?? []);
            $servicesOut = [];

            // Only allow platform services
            foreach ($allowed as $key) {
                $val = $servicesIn[$key] ?? 0;

                // convert to number safely
                if ($val === '' || $val === null) $val = 0;
                if (!is_numeric($val)) {
                    return ['__error' => true, '__message' => "Invalid price for {$platform} {$key}"];
                }

                $val = (float) $val;
                if ($val < 0) $val = 0;

                $servicesOut[$key] = $val;
            }

            $out['platforms'][] = [
                'platform'   => $platform,
                'username'   => trim((string) ($p['username'] ?? '')),
                'followers'  => (int) ($p['followers'] ?? 0),
                'engagement' => isset($p['engagement']) && $p['engagement'] !== '' ? (float) $p['engagement'] : null,
                'services'   => $servicesOut,
            ];
        }

        // Prevent duplicate platforms if you want:
        // (optional rule)
        // $names = array_column($out['platforms'], 'platform');
        // if (count($names) !== count(array_unique($names))) {
        //     return ['__error' => true, '__message' => 'Duplicate platforms are not allowed.'];
        // }

        return $out;
    }
}