<?php

namespace App\Listeners;

use App\Models\User;
use App\Services\WebPushService;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Log;

class SendWebPushOnNotification
{
    public function __construct(private WebPushService $push) {}

    public function handle(NotificationSent $event): void
    {
        // Only react to database channel notifications
        if ($event->channel !== 'database') {
            return;
        }

        $user = $event->notifiable;

        // Only send push if the user has subscriptions
        if (!$user instanceof User || $user->pushSubscriptions()->doesntExist()) {
            return;
        }

        $data = $event->notification->toDatabase($user);
        $type = $data['type'] ?? 'notification';

        [$title, $body] = $this->buildMessage($type, $data);
        $url = $this->buildUrl($type, $data);

        try {
            $this->push->notifyUser($user, $title, $body, $url);
        } catch (\Throwable $e) {
            Log::error('WebPush dispatch error: ' . $e->getMessage());
        }
    }

    private function buildMessage(string $type, array $data): array
    {
        $name = $data['name'] ?? 'Quelqu\'un';

        return match ($type) {
            'like'    => ['Nouveau like 🔥',       "$name a aimé votre publication"],
            'follow'  => ['Nouveau abonné 👋',      "$name vous suit maintenant"],
            'comment' => ['Nouveau commentaire 💬', "$name a commenté : " . ($data['comment_body'] ?? '')],
            default   => ['MelanoGeek', 'Vous avez une nouvelle notification'],
        };
    }

    private function buildUrl(string $type, array $data): string
    {
        return match ($type) {
            'like', 'comment' => isset($data['post_id'])
                ? "/posts/{$data['post_id']}"
                : '/notifications',
            'follow' => isset($data['username'])
                ? "/@{$data['username']}"
                : '/notifications',
            default => '/notifications',
        };
    }
}
