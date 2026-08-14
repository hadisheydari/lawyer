<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class PaymentReceivedNotification extends Notification
{
    public function __construct(protected Payment $payment, protected string $url) {}

    public function via($notifiable): array
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('پرداخت جدید انجام شد')
            ->icon('/assets/icons/icon-192.png')
            ->body(fa_price($this->payment->amount) . ' توسط ' . ($this->payment->user->name ?? 'کاربر') . ' پرداخت شد.')
            ->data(['url' => $this->url])
            ->options(['TTL' => 300]);
    }
}