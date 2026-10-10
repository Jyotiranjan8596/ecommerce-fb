<?php

namespace App\Support;

use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Notification;

/**
 * Tiny helper to fire in-app notifications to any user, or to every
 * Admin (role 1) / Customer (role 3) / POS (role 4) user in one call.
 *
 * Usage:
 *   Notify::user($someUser, 'Order placed', 'Order #123 has been placed.', 'success', $url);
 *   Notify::admins('New order', 'A new order was placed.', 'info', $url);
 *   Notify::pos($posUser, 'Order update', 'Status changed.', 'info', $url);
 */
class Notify
{
    public const ROLE_ADMIN = 1;
    public const ROLE_CUSTOMER = 3;
    public const ROLE_POS = 4;

    public static function user($user, string $title, string $message, string $type = 'info', ?string $url = null, string $icon = 'fa-bell'): void
    {
        if (! $user) {
            return;
        }

        $user->notify(new GeneralNotification($title, $message, $type, $url, $icon));
    }

    public static function role(int $role, string $title, string $message, string $type = 'info', ?string $url = null, string $icon = 'fa-bell'): void
    {
        $users = User::where('role', $role)->get();

        if ($users->isEmpty()) {
            return;
        }

        Notification::send($users, new GeneralNotification($title, $message, $type, $url, $icon));
    }

    public static function admins(string $title, string $message, string $type = 'info', ?string $url = null, string $icon = 'fa-bell'): void
    {
        static::role(self::ROLE_ADMIN, $title, $message, $type, $url, $icon);
    }

    public static function pos($posUser, string $title, string $message, string $type = 'info', ?string $url = null, string $icon = 'fa-bell'): void
    {
        static::user($posUser, $title, $message, $type, $url, $icon);
    }

    public static function customer($customerUser, string $title, string $message, string $type = 'info', ?string $url = null, string $icon = 'fa-bell'): void
    {
        static::user($customerUser, $title, $message, $type, $url, $icon);
    }
}
