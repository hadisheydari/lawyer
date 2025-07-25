export async function loadPersianDatepickerCDN() {
    if (!document.querySelector('link[href*="persian-datepicker.min.css"]')) {
        const cssLink = document.createElement('link');
        cssLink.rel = 'stylesheet';
        cssLink.href = 'https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css';
        document.head.appendChild(cssLink);
    }

    await loadScriptOnce('https://cdn.jsdelivr.net/npm/persian-date@1.0.5/dist/persian-date.min.js');
    await loadScriptOnce('https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js');

    if (typeof $ !== 'undefined' && $.fn.persianDatepicker) {
        $('input.persian-datepicker').each(function () {
            const $displayInput = $(this);
            const hiddenId = $displayInput.data('pd-target');
            const $hiddenInput = $('#' + hiddenId);

            // مقدار اولیه timestamp از hidden
            const ts = parseInt($hiddenInput.val());
            if (ts && !isNaN(ts)) {
                const pd = new persianDate(ts * 1000);
                $displayInput.val(pd.format('YYYY/MM/DD'));
            }

            $displayInput.persianDatepicker({
                format: 'YYYY/MM/DD',
                autoClose: true,
                calendar: {
                    persian: { enabled: true, locale: 'fa' },
                    gregorian: { enabled: false }
                },
                initialValue: !!$displayInput.val(),
                initialValueType: 'persian',
                onSelect: function (unix) {
                    const timestamp = Math.floor(unix / 1000);
                    $hiddenInput.val(timestamp).trigger('change');
                }
            });
        });
    } else {
        console.error('❌ jQuery یا Persian Datepicker بارگذاری نشده‌اند!');
    }
}

function loadScriptOnce(src) {
    return new Promise((resolve, reject) => {
        if (document.querySelector(`script[src="${src}"]`)) {
            resolve();
            return;
        }
        const script = document.createElement('script');
        script.src = src;
        script.onload = resolve;
        script.onerror = () => reject(new Error(`خطا در بارگذاری اسکریپت: ${src}`));
        document.head.appendChild(script);
    });
}
