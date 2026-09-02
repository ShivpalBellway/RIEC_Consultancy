<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuccessStory extends Model
{
    protected $fillable = [
        'name', 'role', 'image', 'review', 'sort_order', 'status'
    ];
}
