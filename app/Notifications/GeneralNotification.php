<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * A single, reusable in-app notification used across POS, Admin and Customer
 * users for orders, alerts and important system dumps/reports.
 */
class GeneralNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected string $title,
        protected string $message,
        protected string $type = 'info',
        protected ?string $url = null,
        protected string $icon = 'fa-bell'
    ) {
    }

    /**
     * Database only — fast, zero external setup.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type, // info | success | warning | danger
            'url' => $this->url,
            'icon' => $this->icon,
        ];
    }
}
