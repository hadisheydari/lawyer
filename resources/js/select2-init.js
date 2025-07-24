// resources/js/select2-loader.js

export async function loadSelect2CDN() {
    function loadScript(url) {
        return new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = url;
            script.onload = () => resolve();
            script.onerror = () => reject(`Failed to load script ${url}`);
            document.head.appendChild(script);
        });
    }

    function loadCss(url) {
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = url;
        document.head.appendChild(link);
    }

    // بارگذاری CSS
    loadCss('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');

    // اگر jQuery لود نشده باشه، لودش کن
    if (typeof jQuery === 'undefined') {
        await loadScript('https://code.jquery.com/jquery-3.7.1.min.js');
    }

    // بارگذاری Select2
    await loadScript('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js');

    // initialize
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            dir: 'rtl' // یا 'ltr' اگه لازم بود تغییرش بدی
        });
    });
}
