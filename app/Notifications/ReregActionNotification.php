<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReregActionNotification extends Notification
{
    use Queueable;

    public function __construct(
        public array $payload // must include 'dedupe_key'
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        // Keep this structure consistent across the system
        return [
            'dedupe_key' => $this->payload['dedupe_key'] ?? null,

            'title'      => $this->payload['title'] ?? 'Update',
            'message'    => $this->payload['message'] ?? null,

            'org_id'     => $this->payload['org_id'] ?? null,
            'target_sy'  => $this->payload['target_sy_id'] ?? null,

            'form'       => $this->payload['form'] ?? null,      
            'status'     => $this->payload['status'] ?? null,   

            'action_url' => $this->payload['action_url'] ?? null,
            'meta'       => $this->payload['meta'] ?? [],
        ];
    }
}
