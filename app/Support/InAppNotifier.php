<?php

namespace App\Support;

use App\Notifications\ReregActionNotification;
use Illuminate\Notifications\DatabaseNotification;

class InAppNotifier
{
    
    public static function notifyOnce($user, array $payload): void
    {
        if (!$user) return;

        $dedupeKey = $payload['dedupe_key'] ?? null;

        if (!$dedupeKey) {
            // no dedupe → just send normally
            $user->notify(new ReregActionNotification($payload));
            return;
        }

        $existing = DatabaseNotification::query()
            ->where('notifiable_type', get_class($user))
            ->where('notifiable_id', $user->getKey())
            ->where('data->dedupe_key', $dedupeKey)
            ->latest()
            ->first();

        if ($existing) {
            $existing->delete();
        }

        $user->notify(new ReregActionNotification($payload));
        return;

        $user->notify(new ReregActionNotification($payload));
    }


}
