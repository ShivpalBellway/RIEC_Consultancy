<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'type',
        'priority',
        'status',
        'published_at',
        'expires_at',
        'created_by',
        'file_paths',
        'file_names',
        'is_pinned',
        'views',
        'downloads',
    ];

  protected $casts = [
        'file_paths' => 'array',  // ✅ Automatically converts JSON to array
        'file_names' => 'array',  // ✅ Automatically converts JSON to array
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_pinned' => 'boolean',
        'views' => 'integer',
        'downloads' => 'integer',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->where('published_at', '<=', now())
                     ->where(function($q) {
                         $q->whereNull('expires_at')
                           ->orWhere('expires_at', '>=', now());
                     });
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Accessors
    public function getPriorityLabelAttribute()
    {
        $labels = [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'urgent' => 'Urgent',
        ];
        return $labels[$this->priority] ?? 'Normal';
    }

    public function getPriorityColorAttribute()
    {
        $colors = [
            'low' => 'gray',
            'medium' => 'blue',
            'high' => 'orange',
            'urgent' => 'red',
        ];
        return $colors[$this->priority] ?? 'gray';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'draft' => 'Draft',
            'published' => 'Published',
            'archived' => 'Archived',
        ];
        return $labels[$this->status] ?? 'Draft';
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'draft' => 'gray',
            'published' => 'green',
            'archived' => 'red',
        ];
        return $colors[$this->status] ?? 'gray';
    }

    public function getFileIconAttribute()
    {
        if (empty($this->file_paths)) {
            return null;
        }
        
        $filePaths = is_string($this->file_paths) ? json_decode($this->file_paths, true) : $this->file_paths;
        if (empty($filePaths)) {
            return null;
        }
        
        $ext = pathinfo($filePaths[0] ?? '', PATHINFO_EXTENSION);
        $icons = [
            'pdf' => 'fa-regular fa-file-pdf text-red-600',
            'doc' => 'fa-regular fa-file-word text-blue-600',
            'docx' => 'fa-regular fa-file-word text-blue-600',
            'xls' => 'fa-regular fa-file-excel text-green-600',
            'xlsx' => 'fa-regular fa-file-excel text-green-600',
            'jpg' => 'fa-regular fa-file-image text-purple-600',
            'jpeg' => 'fa-regular fa-file-image text-purple-600',
            'png' => 'fa-regular fa-file-image text-purple-600',
            'gif' => 'fa-regular fa-file-image text-purple-600',
            'webp' => 'fa-regular fa-file-image text-purple-600',
            'svg' => 'fa-regular fa-file-image text-purple-600',
            'zip' => 'fa-regular fa-file-archive text-amber-600',
            'rar' => 'fa-regular fa-file-archive text-amber-600',
            'txt' => 'fa-regular fa-file-lines text-gray-600',
            'csv' => 'fa-regular fa-file-csv text-green-600',
        ];
        return $icons[$ext] ?? 'fa-regular fa-file text-gray-600';
    }

    public function getFilePathsArrayAttribute()
    {
        if (is_string($this->file_paths)) {
            return json_decode($this->file_paths, true) ?? [];
        }
        return $this->file_paths ?? [];
    }

    public function getFileNamesArrayAttribute()
    {
        if (is_string($this->file_names)) {
            return json_decode($this->file_names, true) ?? [];
        }
        return $this->file_names ?? [];
    }
}