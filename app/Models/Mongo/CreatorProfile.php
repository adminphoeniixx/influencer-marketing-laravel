<?php

namespace App\Models\Mongo;

use MongoDB\Laravel\Eloquent\Model;

class CreatorProfile extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'creator_profiles';

    protected $fillable = [
        'user_id',
        'display_name',
        'bio',
        'location',
        'categories',
        'languages',
        'platforms',
        'status',
        'rejection_reason',
        'submitted_at',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'user_id'      => 'integer',
        'categories'   => 'array',
        'languages'    => 'array',
        'platforms'    => 'array',
        'submitted_at' => 'datetime',
        'approved_at'  => 'datetime',
        'rejected_at'  => 'datetime',
    ];
}