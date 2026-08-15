const CACHE_NAME = "abdali-cache-v2";
const OFFLINE_URL = "/offline.html";

self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => cache.add(OFFLINE_URL))
            .catch((err) => {
                // اگر کش کردن offline.html به هر دلیلی fail شود،
                // نصب Service Worker را متوقف نکن
                console.warn("Offline page could not be cached:", err);
            })
    );
    self.skipWaiting();
});

self.addEventListener("activate", (event) =>
    event.waitUntil(self.clients.claim()),
);

self.addEventListener("fetch", (event) => {
    if (event.request.mode === "navigate") {
        event.respondWith(
            fetch(event.request).catch(() => caches.match(OFFLINE_URL)),
        );
    }
});

self.addEventListener("push", function (event) {
    if (!event.data) return;
    let payload = {};
    try {
        payload = event.data.json();
    } catch (e) {
        payload = { title: "اعلان جدید", options: { body: event.data.text() } };
    }

    const title = payload.title || "دفتر وکالت ابدالی و جوشقانی";
    const opts = payload.options || {};
    const options = {
        body: opts.body || "",
        icon: opts.icon || "/assets/icons/icon-192.png",
        badge: opts.badge || "/assets/icons/icon-192.png",
        data: opts.data || {},
        dir: "rtl",
        lang: "fa",
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener("notificationclick", function (event) {
    event.notification.close();
    const url = (event.notification.data && event.notification.data.url) || "/";
    event.waitUntil(
        clients
            .matchAll({ type: "window", includeUncontrolled: true })
            .then((windowClients) => {
                for (const client of windowClients) {
                    if (client.url === url && "focus" in client)
                        return client.focus();
                }
                if (clients.openWindow) return clients.openWindow(url);
            }),
    );
});
