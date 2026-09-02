<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'title', 'slug', 'excerpt', 'description', 'image', 'icon', 'sort_order', 'status'
    ];
}
