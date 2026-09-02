<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'type',
        'title',
        'message',
        'link',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
