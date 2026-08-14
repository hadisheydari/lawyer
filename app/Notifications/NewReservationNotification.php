<?php

namespace App\Notifications;

use App\Models\Consultation;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class NewReservationNotification extends Notification
{
    public function __construct(protected Consultation $consultation) {}

    public function via($notifiable): array
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        $clientName = $this->consultation->user->name ?? 'یک موکل';

        return (new WebPushMessage)
            ->title('درخواست نوبت جدید')
            ->icon('/assets/icons/icon-192.png')
            ->body($clientName . ' یک نوبت مشاوره جدید ثبت کرد.')
            ->data(['url' => route('lawyer.consultations.show', $this->consultation->id)])
            ->options(['TTL' => 300]);
    }
}