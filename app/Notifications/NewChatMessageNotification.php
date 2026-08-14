<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class NewChatMessageNotification extends Notification
{
    public function __construct(
        protected string $senderName,
        protected string $messagePreview,
        protected string $url
    ) {}

    public function via($notifiable): array
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('پیام جدید از ' . $this->senderName)
            ->icon('/assets/icons/icon-192.png')
            ->body(mb_strimwidth($this->messagePreview ?: 'یک فایل ارسال شد', 0, 80, '...'))
            ->data(['url' => $this->url])
            ->options(['TTL' => 300]);
    }
}