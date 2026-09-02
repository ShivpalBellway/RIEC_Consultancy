<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'admin_name', 'action', 'module', 'description', 'ip_address',
    ];

    // Add date casting
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public static function log(string $action, string $module, string $description): void
    {
        try {
            static::create([
                'admin_name' => session('admin_name', 'Admin'),
                'action'     => $action,
                'module'     => $module,
                'description'=> $description,
                'ip_address' => request()->ip(),
                'created_at' => now(), // Explicitly set created_at
            ]);
        } catch (\Exception $e) {
            // fail silently — log nahi hua toh app nahi rukegi
        }
    }
}
