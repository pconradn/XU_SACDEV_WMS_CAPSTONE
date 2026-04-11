<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReregActionNotification extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    public function __construct(
        public array $payload
    ) {}

    public function via($notifiable): array
    {
        $channels = ['database', 'broadcast'];

        if (!empty($this->payload['send_mail']) && $this->resolveEmail($notifiable)) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toArray($notifiable): array
    {
        return [
            'dedupe_key' => $this->payload['dedupe_key'] ?? null,

            'title'      => $this->payload['title'] ?? 'Update',
            'message'    => $this->payload['message'] ?? null,

            'org_id'        => $this->payload['org_id'] ?? null,
            'target_sy_id'  => $this->payload['target_sy_id'] ?? null,

            'route' => $this->payload['route'] ?? null,

            'form'   => $this->payload['form'] ?? null,
            'status' => $this->payload['status'] ?? null,

            'meta' => $this->payload['meta'] ?? [],
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        $title = $this->payload['title'] ?? 'SACDEV Notification';
        $message = $this->payload['message'] ?? '';
        $url = $this->payload['route'] ?? null;

        $mail = (new MailMessage)
            ->subject($title)
            ->greeting('Hello ' . ($this->resolveName($notifiable) ?? 'there') . ',')
            ->line($message);

        if (!empty($url)) {
            $mail->action('Open in System', $url);
        }

        return $mail->line('This is an automated message from the SACDEV system.');
    }
    private function resolveEmail($notifiable): ?string
    {
        
        if (!empty($notifiable?->email)) {
            return $notifiable->email;
        }

   
        if (!empty($notifiable?->user?->email)) {
            return $notifiable->user->email;
        }

        return null;
    }

    private function resolveName($notifiable): ?string
    {
        if (!empty($notifiable?->name)) {
            return $notifiable->name;
        }

        if (!empty($notifiable?->user?->name)) {
            return $notifiable->user->name;
        }

        return null;
    }
    public function toBroadcast($notifiable)
    {
        return new \Illuminate\Notifications\Messages\BroadcastMessage([
            'title' => $this->payload['title'] ?? 'Update',
            'message' => $this->payload['message'] ?? null,
            'route' => $this->payload['route'] ?? null,
        ]);
    }
}
