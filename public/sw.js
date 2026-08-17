// public/sw.js
const CACHE_NAME = "lawyer-app-v2"; // هر بار تغییر مهم دادید، این عدد رو افزایش بدید
const OFFLINE_URL = "/offline.html";

const PRECACHE_ASSETS = ["/offline.html", "/manifest.json"];

self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(PRECACHE_ASSETS)),
    );
    self.skipWaiting();
});

self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((keys) =>
                Promise.all(
                    keys
                        .filter((key) => key !== CACHE_NAME)
                        .map((key) => caches.delete(key)),
                ),
            ),
    );
    self.clients.claim();
});

self.addEventListener("fetch", (event) => {
    const { request } = event;

    // ⚠️ حیاتی‌ترین بخش:
    // فقط درخواست‌های GET رو مدیریت کن. هر متد دیگه (POST, PUT, DELETE)
    // از جمله آپلود فایل (multipart/form-data) باید مستقیم و دست‌نخورده
    // به شبکه بره، وگرنه فرم‌ها (مثل ثبت مقاله، آپلود عکس، ارسال پیام چت) می‌شکنن.
    if (request.method !== "GET") {
        return; // اجازه بده مرورگر خودش به‌صورت عادی به شبکه بفرسته
    }

    // فقط درخواست‌های هم‌مبدأ رو دست بزن
    if (new URL(request.url).origin !== self.location.origin) {
        return;
    }

    // برای ناوبری صفحات: تلاش برای شبکه، در صورت آفلاین بودن نمایش offline.html
    if (request.mode === "navigate") {
        event.respondWith(
            fetch(request).catch(() => caches.match(OFFLINE_URL)),
        );
        return;
    }

    // برای بقیه‌ی GETها (استاتیک‌ها): شبکه اول، در صورت خطا کش
    event.respondWith(
        fetch(request)
            .then((response) => {
                const clone = response.clone();
                caches
                    .open(CACHE_NAME)
                    .then((cache) => cache.put(request, clone));
                return response;
            })
            .catch(() => caches.match(request)),
    );
});
