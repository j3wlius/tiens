<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'frequency',
        'recipients',
        'filters',
        'last_sent_at',
        'next_scheduled_at',
        'is_active',
    ];

    protected $casts = [
        'recipients' => 'array',
        'filters' => 'array',
        'last_sent_at' => 'datetime',
        'next_scheduled_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}
