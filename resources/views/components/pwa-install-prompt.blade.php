<div id="pwaInstallBanner" style="display:none;position:fixed;bottom:16px;left:16px;right:16px;z-index:9999;background:#102a43;color:#fff;padding:14px 18px;border-radius:14px;box-shadow:0 10px 30px rgba(0,0,0,0.25);align-items:center;gap:12px;font-family:'Vazirmatn',sans-serif;">
    <i class="fas fa-mobile-alt" style="color:#c5a059;font-size:1.4rem;flex-shrink:0;"></i>
    <div style="flex:1;font-size:0.85rem;line-height:1.6;" id="pwaBannerText">
        این وب‌سایت را به صفحه اصلی گوشی خود اضافه کنید تا مثل یک اپلیکیشن استفاده کنید.
    </div>
    <button id="pwaInstallBtn" style="background:#c5a059;color:#fff;border:none;padding:8px 16px;border-radius:8px;font-weight:700;font-size:0.8rem;cursor:pointer;white-space:nowrap;">نصب</button>
    <button id="pwaCloseBtn" style="background:none;border:none;color:rgba(255,255,255,0.6);font-size:1.1rem;cursor:pointer;">&times;</button>
</div>

{{-- بنر جداگانه: درخواست فعال‌سازی اعلان‌ها --}}
@auth
<div id="pwaNotifBanner" style="display:none;position:fixed;bottom:16px;left:16px;right:16px;z-index:9998;background:#fff;color:#102a43;padding:14px 18px;border-radius:14px;box-shadow:0 10px 30px rgba(0,0,0,0.18);align-items:center;gap:12px;font-family:'Vazirmatn',sans-serif;border:1px solid rgba(197,160,89,0.3);">
    <i class="fas fa-bell" style="color:#c5a059;font-size:1.4rem;flex-shrink:0;"></i>
    <div style="flex:1;font-size:0.85rem;line-height:1.6;">
        برای اینکه پیام‌های جدید را حتی وقتی سایت باز نیست دریافت کنید، اعلان‌ها را فعال کنید.
    </div>
    <button id="pwaEnableNotifBtn" style="background:#102a43;color:#fff;border:none;padding:8px 16px;border-radius:8px;font-weight:700;font-size:0.8rem;cursor:pointer;white-space:nowrap;">فعال‌سازی</button>
    <button id="pwaNotifCloseBtn" style="background:none;border:none;color:#94a3b8;font-size:1.1rem;cursor:pointer;">&times;</button>
</div>
@endauth

<script>
(function () {
    const banner = document.getElementById('pwaInstallBanner');
    const btn = document.getElementById('pwaInstallBtn');
    const closeBtn = document.getElementById('pwaCloseBtn');
    const text = document.getElementById('pwaBannerText');

    const notifBanner = document.getElementById('pwaNotifBanner');
    const notifBtn = document.getElementById('pwaEnableNotifBtn');
    const notifCloseBtn = document.getElementById('pwaNotifCloseBtn');

    const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
    const isIos = /iphone|ipad|ipod/.test(window.navigator.userAgent.toLowerCase());

    // ─── تلاش برای فعال‌سازی نوتیف با یک تپ صریح کاربر (لازم برای iOS Safari) ───
    function showNotifBannerIfNeeded() {
        if (!notifBanner) return; // یعنی کاربر لاگین نیست
        if (localStorage.getItem('pwaNotifDismissed') === '1') return;
        if (typeof Notification === 'undefined') return;
        if (Notification.permission === 'granted' || Notification.permission === 'denied') return;
        notifBanner.style.display = 'flex';
    }

    if (notifBtn) {
        notifBtn.addEventListener('click', async () => {
            if (typeof window.subscribeToPush === 'function') {
                const result = await window.subscribeToPush();
                if (result && result.ok) {
                    notifBanner.style.display = 'none';
                } else if (result && result.reason && result.reason.startsWith('permission-')) {
                    // کاربر رد کرد؛ دیگر مزاحمش نشو
                    notifBanner.style.display = 'none';
                    localStorage.setItem('pwaNotifDismissed', '1');
                }
            }
        });
    }

    if (notifCloseBtn) {
        notifCloseBtn.addEventListener('click', () => {
            notifBanner.style.display = 'none';
            localStorage.setItem('pwaNotifDismissed', '1');
        });
    }

    // ─── بنر نصب PWA ───
    if (isStandalone) {
        // اگر همین الان به‌صورت PWA نصب‌شده باز شده، مستقیم برو سراغ درخواست نوتیف
        showNotifBannerIfNeeded();
        return;
    }

    if (localStorage.getItem('pwaBannerDismissed') === '1') {
        showNotifBannerIfNeeded();
        return;
    }

    let deferredPrompt = null;

    if (isIos) {
        text.textContent = 'برای نصب: دکمه اشتراک‌گذاری (Share) را در سافاری بزنید و «Add to Home Screen» را انتخاب کنید.';
        btn.style.display = 'none';
        banner.style.display = 'flex';
    } else {
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            banner.style.display = 'flex';
        });

        btn.addEventListener('click', async () => {
            if (!deferredPrompt) return;
            deferredPrompt.prompt();
            const choice = await deferredPrompt.userChoice;
            deferredPrompt = null;
            banner.style.display = 'none';

            if (choice.outcome === 'accepted' && typeof window.subscribeToPush === 'function') {
                window.subscribeToPush();
            }
        });

        window.addEventListener('appinstalled', () => {
            if (typeof window.subscribeToPush === 'function') {
                window.subscribeToPush();
            }
        });
    }

    closeBtn.addEventListener('click', () => {
        banner.style.display = 'none';
        localStorage.setItem('pwaBannerDismissed', '1');
        showNotifBannerIfNeeded();
    });
})();
</script>