<?php

namespace App\Support;

use App\Notifications\ReregActionNotification;
use Illuminate\Notifications\DatabaseNotification;

class InAppNotifier
{
    /**
     * Sends a database notification once per user per dedupe_key.
     */
    public static function notifyOnce($user, array $payload): void
    {
        if (!$user) return;

        $dedupeKey = $payload['dedupe_key'] ?? null;
        if (!$dedupeKey) return;

        // Works on MySQL 5.7+/8+ and most MariaDB builds with JSON support
        $exists = DatabaseNotification::query()
            ->where('notifiable_type', get_class($user))
            ->where('notifiable_id', $user->getKey())
            ->where('data->dedupe_key', $dedupeKey)
            ->exists();

        if ($exists) return;

        $user->notify(new ReregActionNotification($payload));
    }
}
