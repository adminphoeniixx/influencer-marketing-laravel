<?php

namespace App\Models\Mongo;

use MongoDB\Laravel\Eloquent\Model;

abstract class BaseMongoModel extends Model
{
    protected $connection = 'mongodb';

    protected $guarded = [];
}