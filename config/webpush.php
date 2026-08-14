<?php

return [
    'vapid' => [
        'subject' => env('VAPID_SUBJECT', env('APP_URL')),
        'public_key' => env('VAPID_PUBLIC_KEY'),
        'private_key' => env('VAPID_PRIVATE_KEY'),
        'pem_file' => env('VAPID_PEM_FILE'),
    ],
    'model' => \NotificationChannels\WebPush\PushSubscription::class,
    'database_connection' => null,
    'table_name' => 'push_subscriptions',
];