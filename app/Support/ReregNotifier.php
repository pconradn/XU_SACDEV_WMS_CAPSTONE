<?php

namespace App\Support;

use App\Models\User;
use App\Notifications\ReregActionNotification;
use Illuminate\Support\Facades\DB;

class ReregNotifier
{
    /**
     * Send in-app notification with dedupe.
     * Dedupe: don’t send same (recipient, org, sy, form, new_status) twice.
     */
    public static function notify(User $recipient, array $data): void
    {
        $required = ['org_id','sy_id','form','new_status','title','message'];
        foreach ($required as $k) {
            if (!array_key_exists($k, $data)) return; // fail-safe
        }

        $dedupeKey = $data['dedupe_key']
            ?? sprintf('rereg:%d:%d:%s:%s', $data['org_id'], $data['sy_id'], $data['form'], $data['new_status']);

        // Check existing notification with same dedupe_key for this user
        $exists = DB::table('notifications')
            ->where('notifiable_type', User::class)
            ->where('notifiable_id', $recipient->id)
            ->where('data->dedupe_key', $dedupeKey)
            ->exists();

        if ($exists) return;

        $payload = $data + ['dedupe_key' => $dedupeKey];

        $recipient->notify(new ReregActionNotification($payload));
    }
}
