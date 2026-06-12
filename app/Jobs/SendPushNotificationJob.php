<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Services\NotificationService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendPushNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   public function __construct(
        public array $tokens,
        public string $title,
        public string $body,
        public array $data = []
    ) {}

    public function handle(NotificationService $service)
    {
        $service->sendPushNotification($this->tokens, $this->title, $this->body, $this->data);
    }
}
