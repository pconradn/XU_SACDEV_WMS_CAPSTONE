<?php

namespace App\Support;

use App\Models\AuditLog;

//for audit logs

class Audit
{
    public static function log(string $event, ?string $message = null, array $context = []): AuditLog
    {
        return AuditLog::create([
            'event' => $event,
            'message' => $message,
            'actor_user_id' => $context['actor_user_id'] ?? null,
            'organization_id' => $context['organization_id'] ?? null,
            'school_year_id' => $context['school_year_id'] ?? null,
            'meta' => $context['meta'] ?? null,
        ]);
    }
}
