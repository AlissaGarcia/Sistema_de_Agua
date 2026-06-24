<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    protected $fillable = [
        'user_id',
        'user_name',
        'route_name',
        'http_method',
        'ip_address',
        'user_agent',
        'request_payload',
    ];

    protected $casts = [
        'request_payload' => 'array',
    ];
}
