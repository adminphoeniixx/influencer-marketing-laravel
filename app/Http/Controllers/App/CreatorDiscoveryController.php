<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Mongo\CreatorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class CreatorDiscoveryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = max(1, min((int)$request->integer('per_page', 12), 48));
        $page    = max(1, (int)$request->integer('page', 1));

        $platform     = $request->string('platform')->toString();
        $category     = $request->string('category')->toString();
        $location     = $request->string('location')->toString();
        $minFollowers = $request->integer('min_followers', null);
        $maxFollowers = $request->integer('max_followers', null);
        $minPrice     = $request->input('min_price');
        $maxPrice     = $request->input('max_price');
        $sort         = $request->string('sort', 'newest')->toString();

        // $payload = (function () use (
        //     $platform,$category,$location,
        //     $minFollowers,$maxFollowers,
        //     $minPrice,$maxPrice,
        //     $sort,$page,$perPage
        // ) {

        //     $query = CreatorProfile::query()
        //         ->where('status','approved');

        //     if ($platform) {
        //         $query->where('platforms','elemMatch',['platform'=>$platform]);
        //     }

        //     if ($category) {
        //         $query->where('categories',$category);
        //     }

        //     if ($location) {
        //         $query->where('location',$location);
        //     }

        //     if ($minFollowers !== null) {
        //         $query->where('platforms.followers','>=',$minFollowers);
        //     }

        //     if ($maxFollowers !== null) {
        //         $query->where('platforms.followers','<=',$maxFollowers);
        //     }

        //     if ($minPrice !== null) {
        //         $query->where('pricing.post','>=',(float)$minPrice);
        //     }

        //     if ($maxPrice !== null) {
        //         $query->where('pricing.post','<=',(float)$maxPrice);
        //     }

        //     $query->orderBy('created_at','desc');

        //     $total = (clone $query)->count();

        //     $items = $query
        //         ->skip(($page-1)*$perPage)
        //         ->take($perPage)
        //         ->get([
        //             '_id',
        //             'user_id',
        //             'display_name',
        //             'bio',
        //             'platforms',
        //             'categories',
        //             'languages',
        //             'pricing',
        //             'location',
        //             'created_at'
        //         ])
        //         ->toArray();

        //     return [
        //         'total'=>$total,
        //         'page'=>$page,
        //         'per_page'=>$perPage,
        //         'items'=>$items,
        //     ];

        // })(); // ✅ THIS EXECUTES THE FUNCTION


        $payload = (function () use ($page,$perPage) {

        $query = CreatorProfile::query()
            ->where('status','approved');

        $total = (clone $query)->count();

            $items = $query
        ->skip(($page - 1) * $perPage)
        ->take($perPage)
        ->get([
            '_id',
            'user_id',
            'display_name',
            'bio',
            'platforms',
            'categories',
            'languages',
            'pricing',
            'location',
            'created_at'
        ])
        ->map(function ($item) {

            return [
                '_id'          => (string) $item->_id,
                'user_id'      => $item->user_id,
                'display_name' => $item->display_name,
                'bio'          => $item->bio,
                'platforms'    => $item->platforms,
                'categories'   => $item->categories,
                'languages'    => $item->languages,
                'pricing'      => $item->pricing,
                'location'     => $item->location,
                'created_at'   => $item->created_at,
            ];

        })
        ->values()
        ->toArray();

        return [
            'total'=>$total,
            'page'=>$page,
            'per_page'=>$perPage,
            'items'=>$items,
        ];

        })();



        return Inertia::render('Brand/BrowseCreators', [
            'creators'=>$payload['items'],
            'meta'=>[
                'total'=>$payload['total'],
                'page'=>$payload['page'],
                'per_page'=>$payload['per_page'],
            ]
        ]);
    }

    public function show($id)
    {
        $creator = CreatorProfile::where('_id', $id)
            ->where('status', 'approved')
            ->first();

        if (!$creator) {
            abort(404);
        }

        // ✅ normalize pricing safely
        $pricing = $creator->pricing ?? [];
        if (is_object($pricing)) $pricing = (array) $pricing;

        $pricing = array_merge([
            'post' => 0,
            'story' => 0,
            'reel' => 0,
        ], $pricing);

        return Inertia::render('Brand/CreatorProfile', [
            'creator' => [
                '_id'          => (string) $creator->_id,
                'user_id'      => (int) ($creator->user_id ?? 0),
                'display_name' => (string) ($creator->display_name ?? ''),
                'bio'          => (string) ($creator->bio ?? ''),
                'platforms'    => $creator->platforms ?? [],
                'categories'   => $creator->categories ?? [],
                'languages'    => $creator->languages ?? [],
                'pricing'      => $pricing,
                'location'     => (string) ($creator->location ?? ''),
                'created_at'   => $creator->created_at,
            ]
        ]);
    }
    

    
}