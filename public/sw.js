self.addEventListener('install', () => self.skipWaiting());
self.addEventListener('activate', (event) => event.waitUntil(self.clients.claim()));

self.addEventListener('push', function (event) {
    if (!event.data) return;
    let payload = {};
    try { payload = event.data.json(); } catch (e) { payload = { title: 'اعلان جدید', options: { body: event.data.text() } }; }

    const title = payload.title || 'دفتر وکالت ابدالی و جوشقانی';
    const opts = payload.options || {};
    const options = {
        body: opts.body || '',
        icon: opts.icon || '/assets/icons/icon-192.png',
        badge: opts.badge || '/assets/icons/icon-192.png',
        data: opts.data || {},
        dir: 'rtl',
        lang: 'fa',
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    const url = (event.notification.data && event.notification.data.url) || '/';
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((windowClients) => {
            for (const client of windowClients) {
                if (client.url === url && 'focus' in client) return client.focus();
            }
            if (clients.openWindow) return clients.openWindow(url);
        })
    );
});