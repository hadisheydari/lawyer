export async function loadPersianDatepickerCDN() {
    // ✅ CSS Persian Datepicker
    const cssLink = document.createElement('link');
    cssLink.rel = 'stylesheet';
    cssLink.href = 'https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css';
    document.head.appendChild(cssLink);

    // ✅ لود persian-date
    await loadScript('https://cdn.jsdelivr.net/npm/persian-date@1.0.5/dist/persian-date.min.js');

    // ✅ لود persian-datepicker
    await loadScript('https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js');

    console.log('✅ Persian Datepicker Loaded Successfully');

    if (typeof $ !== 'undefined' && $.fn.persianDatepicker) {
        $('.persian-datepicker').persianDatepicker({
            format: 'YYYY/MM/DD',
            autoClose: true,
            initialValue: false,
            calendar: {
                persian: { enabled: true, locale: 'fa' },
                gregorian: { enabled: false }
            }
        });
    } else {
        console.error('❌ Persian Datepicker یا jQuery شناسایی نشد!');
    }
}

function loadScript(src) {
    return new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = src;
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
    });
}
