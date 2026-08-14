<div id="pwaInstallBanner" style="display:none;position:fixed;bottom:16px;left:16px;right:16px;z-index:9999;background:#102a43;color:#fff;padding:14px 18px;border-radius:14px;box-shadow:0 10px 30px rgba(0,0,0,0.25);align-items:center;gap:12px;font-family:'Vazirmatn',sans-serif;">
    <i class="fas fa-mobile-alt" style="color:#c5a059;font-size:1.4rem;flex-shrink:0;"></i>
    <div style="flex:1;font-size:0.85rem;line-height:1.6;" id="pwaBannerText">
        این وب‌سایت را به صفحه اصلی گوشی خود اضافه کنید تا مثل یک اپلیکیشن استفاده کنید.
    </div>
    <button id="pwaInstallBtn" style="background:#c5a059;color:#fff;border:none;padding:8px 16px;border-radius:8px;font-weight:700;font-size:0.8rem;cursor:pointer;white-space:nowrap;">نصب</button>
    <button id="pwaCloseBtn" style="background:none;border:none;color:rgba(255,255,255,0.6);font-size:1.1rem;cursor:pointer;">&times;</button>
</div>

<script>
(function () {
    if (localStorage.getItem('pwaBannerDismissed') === '1') return;

    const banner = document.getElementById('pwaInstallBanner');
    const btn = document.getElementById('pwaInstallBtn');
    const closeBtn = document.getElementById('pwaCloseBtn');
    const text = document.getElementById('pwaBannerText');

    const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
    if (isStandalone) return;

    const isIos = /iphone|ipad|ipod/.test(window.navigator.userAgent.toLowerCase());
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
            await deferredPrompt.userChoice;
            deferredPrompt = null;
            banner.style.display = 'none';
        });
    }

    closeBtn.addEventListener('click', () => {
        banner.style.display = 'none';
        localStorage.setItem('pwaBannerDismissed', '1');
    });
})();
</script>