<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessStep extends Model
{
    protected $fillable = ['icon', 'title', 'description', 'sort_order', 'status'];
}
