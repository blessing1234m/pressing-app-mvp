<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public static function createNotification(User $user, string $type, string $title, string $message, array $data = [], $orderId = null): Notification
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'order_id' => $orderId,
            'is_read' => false
        ]);
    }

    public static function notifyOrderStatusUpdate($order, string $newStatus): void
    {
        $statusMessages = [
            'pending' => 'Votre commande #%s est en attente de confirmation.',
            'confirmed' => 'Votre commande #%s a été confirmée par le pressing.',
            'in_progress' => 'Votre commande #%s est en cours de traitement.',
            'completed' => 'Votre commande #%s est terminée et prête à être livrée.',
            'delivered' => 'Votre commande #%s a été livrée.'
        ];

        if (isset($statusMessages[$newStatus])) {
            self::createNotification(
                $order->client,
                'order_update',
                'Mise à jour de commande',
                sprintf($statusMessages[$newStatus], $order->id),
                ['status' => $newStatus],
                $order->id
            );
        }
    }

    public static function notifyNewOrder($order): void
    {
        self::createNotification(
            $order->pressing->owner,
            'new_order',
            'Nouvelle commande',
            'Vous avez reçu une nouvelle commande #' . $order->id,
            ['order_id' => $order->id],
            $order->id
        );
    }

    public static function getUnreadCount(User $user): int
    {
        return Notification::where('user_id', $user->id)
            ->unread()
            ->count();
    }

    public static function getRecentNotifications(User $user, $limit = 10)
    {
        return Notification::where('user_id', $user->id)
            ->with('order')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
