<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class NotificationService
{
    public function sendPushNotification(array $fcm_tokens, string $title, string $body, string $icon = '', array $data = [])
    {
        if (empty($fcm_tokens)) {
            return false;
        }
        $data = collect($data)->map(fn ($value) => (string) $value)->toArray();
        $messaging = (new Factory)
            ->withServiceAccount(config('firebase.credentials'))
            ->createMessaging();

        $notification = Notification::create($title, $body, $icon);

        $message = CloudMessage::new()
                    ->withNotification($notification)
                    ->withData($data);

        $report = $messaging->sendMulticast($message, $fcm_tokens);

        return [
            'success' => $report->successes()->count(),
            'failed'  => $report->failures()->count(),
        ];
    }
}
